<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Log;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;

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
     * Append new user.
     */
    public function user(string $user): self
    {
        $this->users[] = $user;

        return $this;
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
    public function send($parseMode = null): void
    {
        if (! $this->bot || ! $this->message) {
            return;
        }

        foreach ($this->users as $user) {
            try {
                $this->bot->sendMessage($user, $this->message, $parseMode);
            } catch (Exception $e) {
                Log::channel('telegram')->debug($e->getMessage());
            }
        }
    }
}
