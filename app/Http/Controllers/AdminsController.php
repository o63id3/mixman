<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\AdminResource;
use App\Models\Admin;
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
        Gate::authorize('viewAny', Admin::class);

        $admins = Admin::query()
            ->latest()
            ->paginate(config('settings.pagination_size'));

        return Inertia::render('Admins/Index', [
            'admins' => AdminResource::collection($admins),
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
        return Inertia::render('Admins/Create');
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
            'contact_info' => ['string'],
            'notes' => ['string'],
        ]);

        Admin::create($validated);

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin): Response
    {
        AdminResource::withoutWrapping();

        return Inertia::render('Admins/Edit', [
            'admin' => AdminResource::make($admin),
            'can' => [
                'update' => Gate::allows('update', $admin),
                'delete' => Gate::allows('delete', $admin),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin): RedirectResponse
    {
        Gate::authorize('update', $admin);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2'],
            'username' => ['required', 'string', 'min:2', Rule::unique('users', 'username')->ignore($admin->id)],
            'password' => ['string', 'min:4'],
            'contact_info' => ['string'],
            'notes' => ['string'],
        ]);

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
