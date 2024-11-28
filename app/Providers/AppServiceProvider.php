<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Services\DocumentNumberGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(DocumentNumberGenerator::class);
    }

    /**
     * Bootstrap any application services.
     */
    
    public function boot()
    {
        Paginator::defaultView('vendor.pagination.tailwind'); 
    }
    
}
