<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use App\Models\Network;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class ExpensesController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        // Gate::authorize('viewAny', Payment::class);

        $user = type($request->user())->as(User::class);

        $expenses = Expense::query()
            ->with(['user', 'network'])
            ->visibleTo($user)
            // ->filter($filter, $user)
            ->latest()
            ->paginate(config('settings.pagination_size'));

        return Inertia::render('Expenses/Index', [
            'expenses' => ExpenseResource::collection($expenses),
            'can' => [
                'create' => true,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        // Gate::authorize('create', Order::class);

        return Inertia::render('Expenses/Create', [
            'networks' => Network::all(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'network_id' => ['required', 'exists:networks,id'],
            'description' => ['required'],
            'amount' => ['required', 'numeric'],
        ]);

        $validated['user_id'] = $request->user()->id;
        Expense::create($validated);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense): Response
    {
        // Gate::authorize('update', $payment);

        $expense->load(['user', 'network']);

        return Inertia::render('Expenses/Edit', [
            'networks' => Network::all(['id', 'name']),
            'expense' => ExpenseResource::make($expense),
            'can' => [
                'delete' => true,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense): RedirectResponse
    {
        $validated = $request->validate([
            'network_id' => ['required', 'exists:networks,id'],
            'description' => ['required'],
            'amount' => ['required', 'numeric'],
        ]);

        $validated['user_id'] = $request->user()->id;
        $expense->update($validated);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense): RedirectResponse
    {
        // Authorize

        $expense->delete();

        return to_route('expenses.index');
    }
}
