<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RoleServiceProvider extends ServiceProvider
{

    const ADMIN = "ADMINISTRATEUR";
    const SUPER_ADMIN = "SUPER_ADMIN";
    const MEDIATEUR_EMPLOI = "MEDIATEUR_EMPLOI";
    const APPRENANT = "APPRENANT";
    const VIGILE = "VIGILE";
    const VISITEUR = "VISITEUR";
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
