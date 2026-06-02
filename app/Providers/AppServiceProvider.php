<?php

namespace App\Providers;

use App\Mail\Transport\MicrosoftGraphTransport;
use Illuminate\Support\Facades\Mail;
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
        Mail::extend('microsoft-graph', function (array $config): MicrosoftGraphTransport {
            return new MicrosoftGraphTransport(
                $config['tenant_id'] ?? config('services.microsoft.tenant_id'),
                $config['client_id'] ?? config('services.microsoft.client_id'),
                $config['client_secret'] ?? config('services.microsoft.client_secret')
            );
        });
    }
}
