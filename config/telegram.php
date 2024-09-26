<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Telegram bot token
    |--------------------------------------------------------------------------
    |
    | This value will be used to send massages from telegram bot.
    |
    */

    'TELEGRAM_BOT_ACCESS_TOKEN' => env('TELEGRAM_BOT_ACCESS_TOKEN', null),

    /*
    |--------------------------------------------------------------------------
    | Telegram bot token
    |--------------------------------------------------------------------------
    |
    | The massages will be sent to those users.
    |
    */

    'NOTIFIED_USERS' => env('NOTIFIED_USERS', null),
];
