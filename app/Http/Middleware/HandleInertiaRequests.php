<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Resources\UserResource;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;

final class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        if (auth()->check()) {
            $user = type($request->user())->as(User::class);
            UserResource::withoutWrapping();
            // $user->loadBalance();
            $user = UserResource::make($user);
        }

        return [
            ...parent::share($request),
            'pendingOrders' => Order::pending()->count(),
            'auth' => [
                'user' => $user ?? null,
            ],
        ];
    }
}
