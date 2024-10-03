<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use App\Models\Network;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class ExpensesController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Expense::class);

        $user = type($request->user())->as(User::class);

        $expenses = Expense::query()
            ->with(['user', 'network'])
            ->visibleTo($user)
            ->latest()
            ->paginate(config('settings.pagination_size'));

        return Inertia::render('Expenses/Index', [
            'expenses' => ExpenseResource::collection($expenses),
            'can' => [
                'create' => Gate::authorize('create', Expense::class),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response
    {
        Gate::authorize('create', Expense::class);

        $user = type($request->user())->as(User::class);

        return Inertia::render('Expenses/Create', [
            'networks' => Network::visibleTo($user)->get(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Expense::class);

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
    public function show(Expense $expense): Response
    {
        Gate::authorize('view', $expense);

        $expense->load(['user', 'network']);

        return Inertia::render('Expenses/Show', [
            'expense' => ExpenseResource::single($expense),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense): Response
    {
        Gate::authorize('update', $expense);

        $expense->load(['user', 'network']);

        return Inertia::render('Expenses/Edit', [
            'networks' => Network::all(['id', 'name']),
            'expense' => ExpenseResource::single($expense),
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
        Gate::authorize('update', $expense);

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
        Gate::authorize('delete', $expense);

        $expense->delete();

        return to_route('expenses.index');
    }
}
