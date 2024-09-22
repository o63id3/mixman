<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
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

        $transactions = Transaction::query()
            ->with('seller')
            ->latest()
            ->latest('id')
            ->visibleTo($user)
            ->paginate(config('settings.pagination_size'));

        return Inertia::render('Transactions/Index', [
            'transactions' => TransactionResource::collection($transactions),
            'filters' => [
                'seller' => $request->get('seller'),
            ],
        ]);
    }
}
