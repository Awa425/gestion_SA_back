<?php

namespace App\Providers;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\User;

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
        Gate::define('manage_promo', function (User $user) {
            return User::isSuperAdmin($user) ;
        });
        Gate::define('view_promo', function (User $user) {
            return User::isAdmin($user) ;
        });
    }
}
