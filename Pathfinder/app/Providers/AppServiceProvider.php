<?php

namespace App\Providers;

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
        // TODO: Remove this block before deploying to production.
        // This is a workaround for env() not loading in the local dev server (php artisan serve).
        // In production, env() works correctly with php-fpm/config:cache, so this is not needed.
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.transport' => 'smtp',
            'mail.mailers.smtp.host' => 'smtp.gmail.com',
            'mail.mailers.smtp.port' => 587,
            'mail.mailers.smtp.username' => 'chase.exia@gmail.com',
            'mail.mailers.smtp.password' => 'ixtg wccs qgbz ewey',
            'mail.from.address' => 'chase.exia@gmail.com',
            'mail.from.name' => 'Pathfinder',
        ]);
    }
}
