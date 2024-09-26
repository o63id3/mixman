<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\OrderStatusEnum;
use App\Events\NewOrder;
use App\Jobs\SendNewOrderTelegramMassage;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class SellerOrdersController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $seller = type($request->user())->as(User::class);

        $order = Order::create([
            'seller_id' => $seller->id,
            'status' => OrderStatusEnum::Pending,
        ]);

        // defer(fn () => event(new NewOrder($order)));
        // event(new NewOrder($order));
        SendNewOrderTelegramMassage::dispatch($order);

        return back();
    }
}
