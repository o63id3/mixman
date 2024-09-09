<?php

declare(strict_types=1);

arch('models')
    ->expect('App\Models')
    ->toHaveMethod('casts')
    ->ignoring('App\Models\Concerns')
    ->toExtend('Illuminate\Database\Eloquent\Model')
    ->ignoring('App\Models\Concerns')
    ->toOnlyBeUsedIn([
        'App\Concerns',
        'App\Console',
        'App\EventActions',
        'App\Filament',
        'App\Http',
        'App\Jobs',
        'App\Observers',
        'App\Mail',
        'App\Models',
        'App\Notifications',
        'App\Policies',
        'App\Providers',
        'App\Queries',
        'App\Rules',
        'App\Services',
        'Database\Factories',
        'Database\Seeders',
    ])->ignoring('App\Models\Concerns');

arch('ensure factories', function () {
    expect($models = getModels())->toHaveCount(1);

    foreach ($models as $model) {
        /* @var \Illuminate\Database\Eloquent\Factories\HasFactory $model */
        expect($model::factory())
            ->toBeInstanceOf(Illuminate\Database\Eloquent\Factories\Factory::class);
    }
});

/**
 * Get all models in the app/Models directory.
 *
 * @return array<int, class-string<\Illuminate\Database\Eloquent\Model>>
 */
function getModels(): array
{
    $models = type(glob(__DIR__.'/../../app/Models/*.php'))->asArray();

    return collect($models)
        ->map(function ($file) {
            return 'App\Models\\'.basename($file, '.php');
        })->toArray();
}
