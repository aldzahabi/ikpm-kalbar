<?php

namespace App\Providers;

use App\Models\FinanceTransaction;
use App\Models\Santri;
use App\Observers\FinanceTransactionObserver;
use App\Observers\SantriObserver;
use Illuminate\Support\Facades\URL;
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
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        FinanceTransaction::observe(FinanceTransactionObserver::class);
        Santri::observe(SantriObserver::class);
    }
}
