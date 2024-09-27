<?php

declare(strict_types=1);

namespace App\Services;

use TelegramBot\Api\BotApi;

final class TelegramService
{
    /**
     * Telegram bot.
     */
    private BotApi $bot;

    /**
     * List of users to send message to.
     */
    private array $users;

    /**
     * Message content.
     */
    private string $message;

    /**
     * Initialize the bot.
     */
    public function __construct()
    {
        if (
            ! $accessToken = config('telegram.TELEGRAM_BOT_ACCESS_TOKEN')
        ) {
            return;
        }

        $this->bot = new BotApi($accessToken);
    }

    /**
     * Set users.
     */
    public function users(array $users): self
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Set message.
     */
    public function message(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Send the message.
     */
    public function send(): void
    {
        if (! $this->bot || ! $this->message) {
            return;
        }

        foreach ($this->users as $user) {
            $this->bot->sendMessage($user, $this->message);
        }
    }
}
