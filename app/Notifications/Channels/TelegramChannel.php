<?php

declare(strict_types=1);

namespace App\Notifications\Channels;

use App\Services\TelegramService;
use Illuminate\Notifications\Notification;

final class TelegramChannel
{
    /**
     * Create a new telegram channel instance.
     */
    public function __construct(
        private TelegramService $telegram
    ) {}

    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (! $telegramId = $notifiable->telegram) {
            return;
        }

        $data = $notification->toTelegram($notifiable);
        $this
            ->telegram
            ->user($telegramId)
            ->message($data->message)
            ->send('Markdown');
    }
}
