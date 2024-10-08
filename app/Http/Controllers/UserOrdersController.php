<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\OrderStatusEnum;
use App\Jobs\SendNewOrderTelegramMassage;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class UserOrdersController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $user = type($request->user())->as(User::class);

        $order = Order::create([
            'orderer_id' => $user->id,
            'status' => OrderStatusEnum::Pending,
        ]);

        SendNewOrderTelegramMassage::dispatch($order);

        return back();
    }
}
