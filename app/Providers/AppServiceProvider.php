<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Webkul\Security\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(Authenticatable::class, User::class);
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // in case the application is hosted in a subdirectory
        $prefix = trim(parse_url(config('app.url'), PHP_URL_PATH), '/');
        $base = $prefix ? "/{$prefix}" : '';

        Livewire::setScriptRoute(function ($handle) use ($base) {
            return config('app.debug')
                ? Route::get("{$base}/livewire/livewire.js", $handle)
                : Route::get("{$base}/livewire/livewire.min.js", $handle);
        });

        Livewire::setUpdateRoute(function ($handle) use ($base) {
            return Route::post("{$base}/livewire/update", $handle);
        });
    }
}
