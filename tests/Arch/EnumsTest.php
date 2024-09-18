<?php

declare(strict_types=1);

arch('enums')
    ->expect('App\Enums')
    ->toBeEnums()
    ->toExtendNothing()
    ->toUseNothing()
    ->toHaveMethod('toArray')
    ->toOnlyBeUsedIn([
        'Database\Factories',
        'App\Console\Commands',
        'App\Http\Requests',
        'App\Http\Controllers',
        'App\Models',
    ]);
