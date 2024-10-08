<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Http\Filters\UserFilter;
use App\Http\Resources\UserResource;
use App\Models\Network;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

final class UsersController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, UserFilter $filter): Response
    {
        Gate::authorize('viewAny', User::class);

        $user = type($request->user())->as(User::class);

        $users = User::query()
            ->with(['network'])
            ->visibleTo($user)
            ->withBalance()
            ->filter($filter, $user)
            ->latest()
            ->paginate(config('settings.pagination_size'));

        return Inertia::render('Users/Index', [
            'users' => UserResource::collection($users),
            'networks' => Network::visibleTo($user)->get(['id', 'name']),
            'filters' => $filter->filters,
            'sorts' => $filter->sorts,
            'can' => [
                'create' => Gate::allows('create', User::class),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response
    {
        Gate::authorize('create', User::class);

        $user = type($request->user())->as(User::class);

        return Inertia::render('Users/Create', [
            'networks' => Network::visibleTo($user)->get(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2'],
            'username' => ['required', 'string', 'min:2', Rule::unique('users', 'username')],
            'password' => ['required', 'string', 'min:4'],
            'role' => ['required', Rule::enum(RoleEnum::class)],
            'percentage' => ['required_if:role,seller', 'numeric'],
            'network_id' => ['required_if:role,seller', 'required_if:role,partner', 'exists:networks,id'],
            'contact_info' => ['sometimes'],
            'notes' => ['sometimes'],
        ]);

        if (array_key_exists('percentage', $validated)) {
            $validated['percentage'] /= 100;
        }

        $user = type($request->user())->as(User::class);

        if (! $user->isAhmed()) {
            $validated['role'] = 'seller';
        }

        User::create($validated);

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, User $user): Response
    {
        Gate::authorize('update', $user);

        $auth = type($request->user())->as(User::class);

        $user->load('network');

        return Inertia::render('Users/Edit', [
            'user' => UserResource::single($user),
            'networks' => Network::visibleTo($auth)->get(['id', 'name']),
            'can' => [
                'update' => Gate::allows('update', $user),
                'delete' => Gate::allows('delete', $user),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2'],
            'username' => ['required', 'string', 'min:2', Rule::unique('users', 'username')->ignore($user->id)],
            'password' => ['string', 'min:4'],
            'role' => ['required', Rule::enum(RoleEnum::class)],
            'percentage' => ['required_if:role,seller', 'numeric'],
            'network_id' => ['required_if:role,seller', 'exists:networks,id'],
            'contact_info' => ['sometimes'],
            'notes' => ['sometimes'],
        ]);

        if (array_key_exists('percentage', $validated)) {
            $validated['percentage'] /= 100;
        }

        $user->update($validated);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        $user->delete();

        return to_route('users.index');
    }
}
