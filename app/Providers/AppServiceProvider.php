<?php

namespace App\Providers;

use App\Models\Album;
use App\Policies\AlbumPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        //
    }

    protected array $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Album::class => AlbumPolicy::class,
    ];
}
