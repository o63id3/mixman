<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Order;
use App\Notifications\Channels\TelegramChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

final class NewOrderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Order $order
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [TelegramChannel::class];
    }

    /**
     * Get the telegram representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toTelegram(object $notifiable): array
    {
        $appURL = config('app.url');
        $url = "{$appURL}/orders/{$this->order->id}/edit";
        $message = "*{$this->order->orderer->name}* أرسل طلباً جديداً !!\nبإمكانك متابعة الطلب من خلال الرابط: \n{$url}";

        return [
            'message' => $message,
        ];
    }
}
