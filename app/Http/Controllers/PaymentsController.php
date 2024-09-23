<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Filters\PaymentFilter;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

final class PaymentsController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, PaymentFilter $filter): Response
    {
        Gate::authorize('viewAny', Payment::class);

        $seller = type($request->user())->as(User::class);

        $payments = Payment::query()
            ->with(['seller', 'registerer'])
            ->visibleTo($seller)
            ->filter($filter)
            ->latest()
            ->paginate(config('settings.pagination_size'));

        return Inertia::render('Payments/Index', [
            'payments' => PaymentResource::collection($payments),
            'sellers' => Seller::all(),
            'filters' => $filter->filters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Payment::class);

        return Inertia::render('Payments/Create', [
            'sellers' => Seller::get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Payment::class);

        $validated = $request->validate([
            'seller_id' => ['required', Rule::exists('sellers', 'id')],
            'amount' => ['required', 'numeric'],
            'notes' => ['string'],
        ]);

        $request->user()->payments()->create($validated);

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
            'sellers' => Seller::get(),
            'payment' => PaymentResource::make($payment),
            'can' => [
                'delete' => Gate::allows('delete', $payment),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment): RedirectResponse
    {
        Gate::authorize('update', $payment);

        $validated = $request->validate([
            'seller_id' => ['required', Rule::exists('sellers', 'id')],
            'amount' => ['required', 'numeric'],
            'notes' => ['string'],
        ]);

        $payment->update($validated);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment): RedirectResponse
    {
        Gate::authorize('update', $payment);

        $payment->delete();

        return to_route('payments.index');
    }
}
