<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\NewOrder;
use TelegramBot\Api\BotApi;

final class SendNewOrderTelegramMassage
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewOrder $event): void
    {
        if (
            ! $accessToken = config('telegram.TELEGRAM_BOT_ACCESS_TOKEN')
        ) {
            return;
        }

        if (
            $users = explode(',', config('telegram.NOTIFIED_USERS'))
        ) {
            return;
        }

        $bot = new BotApi($accessToken);

        $appURL = config('app.url');
        $url = "{$appURL}orders/{$event->order->id}/edit";
        $message = "البائع {$event->order->seller->name} أرسل طلباً جديداً!!\nبإمكانك متابعة الطلب من خلال الرابط التالي {$url}";

        foreach ($users as $user) {
            $bot->sendMessage($user, $message);
        }
    }
}
