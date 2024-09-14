<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

final class PaymentsController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', Payment::class);

        $payments = Payment::query()
            ->with(['seller', 'registerer'])
            ->latest()
            ->latest('id')
            ->paginate(10);

        return Inertia::render('Payments/Index', [
            'payments' => PaymentResource::collection($payments),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Payment::class);

        return Inertia::render('Payments/Create', [
            'sellers' => User::sellers()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Payment::class);

        $validated = $request->validate([
            'seller_id' => ['required', Rule::exists('users', 'id')],
            'amount' => ['required', 'numeric'],
            'notes' => ['string'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            User::find($validated['seller_id'])->increment('balance', $validated['amount']);
            $request->user()->payments()->create($validated);
        });

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment): Response
    {
        Gate::authorize('update', $payment);

        $payment->load(['seller', 'registerer']);

        PaymentResource::withoutWrapping();

        return Inertia::render('Payments/Edit', [
            'sellers' => User::sellers()->get(),
            'payment' => PaymentResource::make($payment),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment): RedirectResponse
    {
        Gate::authorize('update', $payment);

        $validated = $request->validate([
            'seller_id' => ['required', Rule::exists('users', 'id')],
            'amount' => ['required', 'numeric'],
            'notes' => ['string'],
        ]);

        DB::transaction(function () use ($payment, $validated) {
            $seller = User::find($validated['seller_id']);
            $seller->decrement('balance', $payment->amount);
            $seller->increment('balance', $validated['amount']);

            $payment->update($validated);
        });

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment): RedirectResponse
    {
        Gate::authorize('update', $payment);

        DB::transaction(function () use ($payment) {
            $payment->seller()->decrement('balance', $payment->amount);
            $payment->delete();
        });

        return to_route('payments.index');
    }
}
