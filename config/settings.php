<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Pagination size
    |--------------------------------------------------------------------------
    |
    | This value is the default page size of the application,
    | which will be used when a pagination takes a place.
    |
    */

    'pagination_size' => env('PAGINATION_SIZE', 10),

    /*
    |--------------------------------------------------------------------------
    | Seller orders per day
    |--------------------------------------------------------------------------
    |
    | This value is the default rate limit for user orders per day.
    |
    */

    'user_orders_per_day' => env('USER_ORDERS_PER_DAY', 1),
];
