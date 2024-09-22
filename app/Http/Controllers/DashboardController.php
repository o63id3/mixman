<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\RegionResource;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Region;
use App\Models\Seller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

final class DashboardController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $user = type($request->user())->as(User::class);

        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek();

        $statistics = [
            'total_debuts' => Transaction::query()
                ->visibleTo($user)
                ->sum('amount'),
            'number_of_pending_orders' => Order::query()
                ->visibleTo($user)
                ->pending()
                ->count(),
            'number_of_returned_orders_last_week' => Order::query()
                ->visibleTo($user)
                ->returned()
                // ->whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])
                ->count(),
            'number_of_completed_orders_last_week' => Order::query()
                ->visibleTo($user)
                ->completed()
                // ->whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])
                ->count(),
        ];

        if ($user->isAdmin()) {
            $maxSeller = Transaction::select(['seller_id', DB::raw('SUM(amount) as total_amount')])
                ->with('seller')
                ->groupBy('seller_id')
                ->orderBy('total_amount')
                ->having('total_amount', '<', 0)
                ->first();

            $maxRegion = Payment::query()
                ->select([DB::raw('SUM(amount) as total_amount'), 'region_id'])
                // ->whereBetween('payments.created_at', [$startOfLastWeek, $endOfLastWeek])
                ->join('users', 'users.id', '=', 'seller_id')
                ->groupBy('region_id')
                ->orderByDesc('total_amount')
                ->first();

            $statistics = array_merge($statistics, [
                'max_debut_seller' => [
                    'seller' => $maxSeller ? $maxSeller->seller : null,
                    'amount' => $maxSeller ? $maxSeller->total_amount : 0,
                ],
                'max_region_income' => [
                    'region' => $maxRegion ? RegionResource::make(Region::find($maxRegion->region_id)) : null,
                    'amount' => $maxRegion ? $maxRegion->total_amount : 0,
                ],
                'sellers_count' => Seller::count(),
            ]);
        }

        return Inertia::render('Dashboard', $statistics);
    }
}
