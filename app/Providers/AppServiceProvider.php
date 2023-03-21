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
        Schema::defaultStringLength(191);

        Gate::define('manage', function (User $user) {
            return User::isSuperAdmin($user) ;
        });
        Gate::define('view', function (User $user) {
            return User::isAdmin($user) ;
        });
        Gate::define('vigil_job', function (User $user) {
            return User::isVigile($user) ;
        });
    }
}
