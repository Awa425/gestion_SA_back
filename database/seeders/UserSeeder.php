<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Providers\RoleServiceProvider;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $superAdmin = Role::where('libelle', RoleServiceProvider::SUPER_ADMIN)->pluck('id')->first();
        User::factory()->create([
            'name' => 'Admin',
            'prenom' => 'Admin',
            'telephone' => '778338123',
            'email' => 'admin@gmail.com',
            'role_id' => $superAdmin,
        ]);


    }


}
