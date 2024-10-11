<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\OrderStatusEnum;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

final class UserOrdersController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $user = type($request->user())->as(User::class);

        $order = $user
            ->orders()
            ->create([
                'status' => OrderStatusEnum::Pending,
            ]);

        Notification::sendNow($order->manager, new NewOrderNotification($order));

        return back();
    }
}
