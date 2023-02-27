<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Providers\RoleServiceProvider;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Role::insert([
            ['libelle'=> RoleServiceProvider::ADMIN],
            ['libelle'=> RoleServiceProvider::SUPER_ADMIN],
            ['libelle'=> RoleServiceProvider::VIGILE],
            ['libelle'=> RoleServiceProvider::VISITEUR],
            ['libelle'=> RoleServiceProvider::APPRENANT],
            ['libelle'=> RoleServiceProvider::MEDIATEUR_EMPLOI]
        ]);
    }
}
