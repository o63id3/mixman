<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Http\Resources\UserResource;
use App\Models\Admin;
use App\Models\Network;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

final class AdminsController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', User::class);

        $admins = User::query()
            ->latest()
            ->paginate(config('settings.pagination_size'));

        return Inertia::render('Admins/Index', [
            'admins' => UserResource::collection($admins),
            'can' => [
                'create' => Gate::allows('create', Admin::class),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Admins/Create', [
            'networks' => Network::all('id', 'name'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2'],
            'username' => ['required', 'string', 'min:2', Rule::unique('users', 'username')],
            'password' => ['required', 'string', 'min:4'],
            'role' => ['required', Rule::enum(RoleEnum::class)],
            'percentage' => ['required_if:role,seller', 'numeric'],
            'network_id' => ['required_if:role,seller', 'exists:networks,id'],
            'contact_info' => ['sometimes'],
            'notes' => ['sometimes'],
        ]);

        if (array_key_exists('percentage', $validated)) {
            $validated['percentage'] /= 100;
        }

        User::create($validated);

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $admin): Response
    {
        $admin->load('network');

        return Inertia::render('Admins/Edit', [
            'admin' => UserResource::make($admin),
            'networks' => Network::all('id', 'name'),
            'can' => [
                'update' => Gate::allows('update', $admin),
                'delete' => Gate::allows('delete', $admin),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $admin): RedirectResponse
    {
        Gate::authorize('update', $admin);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2'],
            'username' => ['required', 'string', 'min:2', Rule::unique('users', 'username')->ignore($admin->id)],
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

        $admin->update($validated);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin): RedirectResponse
    {
        Gate::authorize('delete', $admin);

        $admin->delete();

        return to_route('admins.index');
    }
}
