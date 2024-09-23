<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Filters\TransactionFilter;
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
    public function __invoke(Request $request, TransactionFilter $filter): Response
    {
        $user = type($request->user())->as(User::class);

        $transactions = Transaction::query()
            ->with('seller')
            ->visibleTo($user)
            ->filter($filter)
            ->latest()
            ->paginate(config('settings.pagination_size'));

        return Inertia::render('Transactions/Index', [
            'transactions' => TransactionResource::collection($transactions),
            'sellers' => Seller::all(),
            'filters' => $filter->filters,
            'sorts' => $filter->sorts,
        ]);
    }
}
