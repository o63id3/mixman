<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Notifications\Channels\TelegramChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

final class NetworksWeeklyReportNotification extends Notification
{
    use Queueable;

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
    public function toTelegram(object $notifiable): string
    {
        $url = config('app.url').'/reports';

        return <<<EOD
            تم إعداد تقارير الأسبوع !
            يرجى مراجعة صفحة التقارير عبر الرابط:
            {$url}
        EOD;
    }
}
