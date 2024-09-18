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
        $transactions = Transaction::query()
            ->with('seller')
            ->latest()
            ->latest('id')
            ->paginate(10);

        return Inertia::render('Transactions/Index', [
            'transactions' => TransactionResource::collection($transactions),
            'sellers' => User::sellers()->get(),
            'filters' => [
                'seller' => $request->get('seller'),
            ],
        ]);
    }
}
