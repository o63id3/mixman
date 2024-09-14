<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

final class TransactionsController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $transactions = DB::table('orders')
            ->select([
                'orders.id',
                'orders.seller_id',
                DB::raw('SUM(-total_price_for_seller) as amount'),
                'orders.created_at',
                DB::raw("'order' as type"),
            ])
            ->when($request->has('seller'), fn ($query) => $query->where('orders.seller_id', $request->get('seller')))
            ->when(! $request->user()->isAdmin(), fn ($query) => $query->where('orders.seller_id', $request->user()->id))
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->groupBy('order_id')
            ->union(
                DB::table('payments')
                    ->select([
                        'id',
                        'seller_id',
                        'amount',
                        'created_at',
                        DB::raw("'payment' as type"),
                    ])
                    ->when($request->has('seller'), fn ($query) => $query->where('payments.seller_id', $request->get('seller')))
                    ->when(! $request->user()->isAdmin(), fn ($query) => $query->where('payments.seller_id', $request->user()->id))

            )
            ->latest()
            ->latest('id')
            ->paginate(10);

        // Cast the created_at field to Carbon (datetime) instance
        $transactions->getCollection()->transform(function ($transaction) {
            $transaction->created_at = \Carbon\Carbon::parse($transaction->created_at);

            return $transaction;
        });

        // Get all unique seller_ids from the transactions
        $sellerIds = $transactions->pluck('seller_id')->unique()->toArray();

        // Eager load sellers by fetching them in a single query
        $sellers = User::whereIn('id', $sellerIds)->get()->keyBy('id');

        // Map the seller data to the transactions
        $transactions->map(function ($transaction) use ($sellers) {
            $transaction->seller = $sellers->get($transaction->seller_id);

            return $transaction;
        });

        return Inertia::render('Transactions/Index', [
            'transactions' => TransactionResource::collection($transactions),
            'sellers' => User::sellers()->get(),
            'filters' => [
                'seller' => $request->get('seller'),
            ],
        ]);
    }
}
