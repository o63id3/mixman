<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Region;
use App\Models\Seller;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

final class DashboardService
{
    /**
     * Get statistics.
     */
    public function getStatistics(User $user): array
    {
        if (! $user->isAdmin()) {
            return $this->getGeneralStatistics($user);
        }

        return array_merge($this->getGeneralStatistics($user), $this->getAdminStatistics());
    }

    /**
     * Get the max seller debut.
     */
    public function getMaxDebutSeller(): array
    {
        $maxSeller = Transaction::select(['seller_id', DB::raw('SUM(amount) as total_amount')])
            ->with('seller')
            ->groupBy('seller_id')
            ->orderBy('total_amount')
            ->having('total_amount', '<', 0)
            ->first();

        return [
            'seller' => $maxSeller ? $maxSeller->seller : null,
            'amount' => $maxSeller ? $maxSeller->total_amount : 0,
        ];
    }

    /**
     * Get the max region income between two dates.
     */
    public function getMaxRegionIncome($start = null, $end = null): array
    {
        $maxRegion = Payment::query()
            ->select([DB::raw('SUM(amount) as total_amount'), 'region_id'])
            ->when($start && $end, fn ($query) => $query->whereBetween('payments.created_at', [$start, $end]))
            ->join('users', 'users.id', '=', 'seller_id')
            ->groupBy('region_id')
            ->orderByDesc('total_amount')
            ->first();

        return [
            'region' => $maxRegion ? Region::find($maxRegion->region_id)->value('name') : null,
            'amount' => $maxRegion ? $maxRegion->total_amount : 0,
        ];
    }

    /**
     * Get the sellers count.
     */
    public function getSellersCount(): int
    {
        return Seller::count();
    }

    /**
     * Get additional statistics for admin users.
     */
    public function getAdminStatistics(): array
    {
        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek();

        return [
            'max_debut_seller' => $this->getMaxDebutSeller(),
            'max_region_income' => $this->getMaxRegionIncome($startOfLastWeek, $endOfLastWeek),
            'sellers_count' => $this->getSellersCount(),
        ];
    }

    /**
     * Get the total debut for all users (if admin) and seller debut (if seller).
     */
    public function getTotalDebut($user): float
    {
        return Transaction::query()
            ->visibleTo($user)
            ->sum('amount');
    }

    /**
     * Get the pending orders count between two dates.
     */
    public function getPendingOrdersCount($user): int
    {
        return Order::query()
            ->visibleTo($user)
            ->pending()
            ->count();
    }

    /**
     * Get the returned orders count between two dates.
     */
    public function getReturnedOrdersCount($user, $start = null, $end = null): int
    {
        return Order::query()
            ->visibleTo($user)
            ->returned()
            ->when($start && $end, fn ($query) => $query->whereBetween('created_at', [$start, $end]))
            ->count();
    }

    /**
     * Get the completed orders count between two dates.
     */
    public function getCompletedOrdersCount($user, $start = null, $end = null): int
    {
        return Order::query()
            ->visibleTo($user)
            ->completed()
            ->when($start && $end, fn ($query) => $query->whereBetween('created_at', [$start, $end]))
            ->count();
    }

    /**
     * Get general statistics for a user.
     */
    public function getGeneralStatistics(User $user): array
    {
        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek();

        return [
            'total_debuts' => $this->getTotalDebut($user),
            'number_of_pending_orders' => $this->getPendingOrdersCount($user),
            'number_of_returned_orders_last_week' => $this->getReturnedOrdersCount($user, $startOfLastWeek, $endOfLastWeek),
            'number_of_completed_orders_last_week' => $this->getCompletedOrdersCount($user, $startOfLastWeek, $endOfLastWeek),
        ];
    }
}
