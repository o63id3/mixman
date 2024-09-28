<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Order;
use App\Services\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class SendNewOrderTelegramMassage implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Order $order,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(TelegramService $telegram): void
    {
        $appURL = config('app.url');
        $url = "{$appURL}orders/{$this->order->id}/edit";
        $message = "*{$this->order->seller->name}* أرسل طلباً جديداً !!\nبإمكانك متابعة الطلب من خلال الرابط: \n{$url}";

        $telegram
            ->users(explode(',', config('telegram.NOTIFIED_USERS')))
            ->message($message)
            ->send('Markdown');
    }
}
