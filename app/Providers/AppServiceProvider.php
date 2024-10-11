<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Model::shouldBeStrict(! app()->isProduction());

        RateLimiter::for('user-orders', fn (Request $request) => app()->isProduction() ? Limit::perDay(config('settings.user_orders_per_day'))->by($request->user()?->id ?: $request->ip()) : Limit::none());

        JsonResource::macro('single', function ($resource) {
            JsonResource::withoutWrapping();

            return new static($resource);
        });
    }
}
