<?php

namespace App\Providers;

use App\Models\Role;
use Closure;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role as SpatieRole;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function handle($request, Closure $next)
    {
        //
        app()->bind('role', function () {
            return new \App\Models\Role();
        });
            // Binding model Role dari Spatie ke model Role Anda
            // $this->app->bind(\Spatie\Permission\Models\Role::class, \App\Models\Role::class);
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
