<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use TelegramBot\Api\BotApi;

final class SendNewOrderTelegramMassage implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Order $order
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
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
        $url = "{$appURL}orders/{$this->order->id}/edit";
        $message = "البائع {$this->order->seller->name} أرسل طلباً جديداً!!\nبإمكانك متابعة الطلب من خلال الرابط التالي {$url}";

        foreach ($users as $user) {
            $bot->sendMessage($user, $message);
        }
    }
}
