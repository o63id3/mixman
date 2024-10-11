<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Resources\UserResource;
use App\Models\Card;
use App\Models\Expense;
use App\Models\Network;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WeeklyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
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
        return [
            ...parent::share($request),
            'pendingOrders' => $request->user() ? Order::visibleTo($request->user())->pending()->count() : null,
            'auth' => [
                'user' => $request->user() ? UserResource::single($request->user()) : null,
                'permissions' => [
                    'users' => [
                        'viewAny' => Gate::allows('viewAny', User::class),
                    ],
                    'networks' => [
                        'viewAny' => Gate::allows('viewAny', Network::class),
                    ],
                    'cards' => [
                        'viewAny' => Gate::allows('viewAny', Card::class),
                    ],
                    'orders' => [
                        'viewAny' => Gate::allows('viewAny', Order::class),
                    ],
                    'payments' => [
                        'viewAny' => Gate::allows('viewAny', Payment::class),
                    ],
                    'expenses' => [
                        'viewAny' => Gate::allows('viewAny', Expense::class),
                    ],
                    'transactions' => [
                        'viewAny' => Gate::allows('viewAny', Transaction::class),
                    ],
                    'reports' => [
                        'viewAny' => Gate::allows('viewAny', WeeklyReport::class),
                    ],
                ],
            ],
        ];
    }
}
