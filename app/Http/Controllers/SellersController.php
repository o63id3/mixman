<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Filters\SellerFilter;
use App\Http\Resources\SellerResource;
use App\Models\Region;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

final class SellersController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, SellerFilter $filter): Response
    {
        Gate::authorize('viewAny', Seller::class);

        $user = type($request->user())->as(User::class);

        $sellers = Seller::query()
            ->filter($filter, $user)
            ->latest()
            ->withBalance()
            ->with(['region:id,name'])
            ->paginate(config('settings.pagination_size'));

        return Inertia::render('Sellers/Index', [
            'sellers' => SellerResource::collection($sellers),
            'regions' => Region::all(),
            'filters' => $filter->filters,
            'sorts' => $filter->sorts,
            'can' => [
                'create' => Gate::allows('create', Seller::class),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Seller::class);

        return Inertia::render('Sellers/Create', [
            'regions' => Region::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Seller::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2'],
            'region_id' => ['required', Rule::exists('regions', 'id')],
            'seller_percentage' => ['required', 'numeric', 'min:1', 'max:100'],
            'username' => ['required', 'string', 'min:2', Rule::unique('users', 'username')],
            'password' => ['required', 'string', 'min:4'],
            'contact_info' => ['string'],
            'notes' => ['string'],
        ]);

        Seller::create($validated);

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seller $seller): Response
    {
        Gate::authorize('update', Seller::class);

        $seller->load('region');

        SellerResource::withoutWrapping();

        return Inertia::render('Sellers/Edit', [
            'seller' => SellerResource::make($seller),
            'regions' => Region::all(),
            'can' => [
                'delete' => Gate::allows('delete', Seller::class),
                'activate' => Gate::allows('activate', User::class),
                'deactivate' => Gate::allows('deactivate', User::class),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seller $seller): RedirectResponse
    {
        Gate::authorize('update', Seller::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2'],
            'region_id' => ['required', Rule::exists('regions', 'id')],
            'username' => ['required', 'string', 'min:2', Rule::unique('users', 'username')->ignore($seller->id)],
            'password' => ['string', 'min:4'],
            'contact_info' => ['string'],
            'notes' => ['string'],
        ]);

        $seller->update($validated);

        return back();
    }
}
