<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Apprenant;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Apprenant::factory(10)->create();

        // $this->call(RoleSeeder::class);
        // $this->call(UserSeeder::class);
    }
}
