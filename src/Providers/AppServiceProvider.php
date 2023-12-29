<?php

namespace UnknowSk\Core\Providers;

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
        if (class_exists(Cashier::class)) {
            Cashier::ignoreMigrations();
            Cashier::useCustomerModel(Tenant::class);
        }
    }
}
