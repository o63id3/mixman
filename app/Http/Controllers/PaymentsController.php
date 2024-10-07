<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Filters\PaymentFilter;
use App\Http\Resources\PaymentResource;
use App\Models\Network;
use App\Models\Payment;
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

        $user = type($request->user())->as(User::class);

        $payments = Payment::query()
            ->with(['recipient', 'user', 'network'])
            ->visibleTo($user)
            ->filter($filter, $user)
            ->latest()
            ->paginate(config('settings.pagination_size'));

        return Inertia::render('Payments/Index', [
            'payments' => PaymentResource::collection($payments),
            'users' => User::visibleTo($user)->benefiter()->get(['id', 'name']),
            'managers' => User::visibleTo($user)->manager()->get(['id', 'name']),
            'networks' => Network::visibleTo($user)->get(['id', 'name']),
            'filters' => $filter->filters,
            'sorts' => $filter->sorts,
            'can' => [
                'create' => Gate::allows('create', Payment::class),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response
    {
        Gate::authorize('create', Payment::class);

        $user = type($request->user())->as(User::class);

        return Inertia::render('Payments/Create', [
            'users' => User::visibleTo($user)->whereNotNull('network_id')->get(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Payment::class);

        $validated = $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')],
            'amount' => ['required', 'numeric'],
            'notes' => ['string'],
        ]);

        $request
            ->user()
            ->payments()
            ->create($validated);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment): Response
    {
        Gate::authorize('view', $payment);

        $payment->load(['recipient', 'user']);

        return Inertia::render('Payments/Show', [
            'payment' => PaymentResource::single($payment),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Payment $payment): Response
    {
        Gate::authorize('update', $payment);
        $user = type($request->user())->as(User::class);

        $payment->load(['recipient', 'user']);

        return Inertia::render('Payments/Edit', [
            'users' => User::visibleTo($user)->get(['id', 'name']),
            'payment' => PaymentResource::single($payment),
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
            'user_id' => ['required', Rule::exists('users', 'id')],
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
