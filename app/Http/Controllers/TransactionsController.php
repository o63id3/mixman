<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Models\Seller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class TransactionsController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $user = type($request->user())->as(User::class);

        $filters = $request->get('filters', []);

        $transactions = Transaction::query()
            ->with('seller')
            ->latest()
            ->latest('id')
            ->visibleTo($user)
            ->when(array_key_exists('seller', $filters), fn ($query) => $query->whereIn('seller_id', explode(',', $filters['seller'])))
            ->paginate(config('settings.pagination_size'));

        return Inertia::render('Transactions/Index', [
            'transactions' => TransactionResource::collection($transactions),
            'sellers' => Seller::all(),
            'filters' => $filters,
        ]);
    }
}
