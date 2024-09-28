<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\NewOrder;
use App\Services\TelegramService;

final class SendNewOrderTelegramMassage
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(NewOrder $event, TelegramService $telegram): void
    {
        $appURL = config('app.url');
        $url = "{$appURL}orders/{$event->order->id}/edit";
        $message = "البائع {$event->order->seller->name} أرسل طلباً جديداً!!\nبإمكانك متابعة الطلب من خلال الرابط التالي {$url}";

        $telegram
            ->users(explode(',', config('telegram.NOTIFIED_USERS')))
            ->message($message)
            ->send();
    }
}
