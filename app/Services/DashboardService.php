<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Network;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

final class DashboardService
{
    /**
     * Get statistics.
     */
    public function getStatistics(User $user): array
    {
        if (! $user->isAhmed()) {
            return $this->getGeneralStatistics($user);
        }

        return array_merge($this->getGeneralStatistics($user), $this->getAdminStatistics());
    }

    /**
     * Get the max network income between two dates.
     */
    public function getMaxNetworkIncome($start = null, $end = null): array
    {
        $network = Network::query()
            ->withSum(['payments as total_amount' => function ($query) use ($start, $end) {
                $query->when($start && $end, fn () => $query
                    ->whereBetween('payments.created_at', [$start, $end]))
                    ->visibleToAhmed();
            }], 'amount')
            ->orderByDesc('total_amount')
            ->first();

        return [
            'network' => $network->name,
            'amount' => $network ? $network->total_amount : 0,
        ];
    }

    /**
     * Get the total income between two dates.
     */
    public function getTotalIncome($start = null, $end = null): float
    {

        $income = (float) Payment::query()
            ->visibleToAhmed()
            ->when($start && $end, fn ($query) => $query->whereBetween('created_at', [$start, $end]))
            ->sum('amount');

        return $income;
    }

    /**
     * Get additional statistics for admin users.
     */
    public function getAdminStatistics(): array
    {
        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek();

        return [
            'max_network_income' => $this->getMaxNetworkIncome($startOfLastWeek, $endOfLastWeek),
            'total_income' => $this->getTotalIncome($startOfLastWeek, $endOfLastWeek),
        ];
    }

    /**
     * Get the total debut for all users (if admin) and seller debut (if seller).
     */
    public function getTotalDebut($user): float
    {
        return (float) Transaction::query()
            ->visibleTo($user)
            ->when($user->isAhmed(), fn ($query) => $query->where(fn ($query) => $query
                ->where('manager_id', $user->id)
                ->orWhere('user_id', $user->id)
            ))
            ->sum('amount');
    }

    /**
     * Get the pending orders count between two dates.
     */
    public function getPendingOrdersCount($user): int
    {
        return Order::query()
            ->visibleTo($user)
            ->when($user->isAhmed(), fn ($query) => $query->where('manager_id', $user->id))
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
            ->when($user->isAhmed(), fn ($query) => $query->where('manager_id', $user->id))
            ->when($start && $end, fn ($query) => $query->whereBetween('created_at', [$start, $end]))
            ->returned()
            ->count();
    }

    /**
     * Get the completed orders count between two dates.
     */
    public function getCompletedOrdersCount($user, $start = null, $end = null): int
    {
        return Order::query()
            ->visibleTo($user)
            ->when($user->isAhmed(), fn ($query) => $query->where('manager_id', $user->id))
            ->when($start && $end, fn ($query) => $query->whereBetween('created_at', [$start, $end]))
            ->completed()
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
