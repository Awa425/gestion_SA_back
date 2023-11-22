<?php

namespace Database\Seeders;

use App\Models\Referentiel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReferentielSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Referentiel::insert([
            ['libelle'=> 'Dev Web',
            'description' =>'Dev Web',
            'is_active' => 1,
            ],
            ['libelle'=> 'Ref Dig',
            'description' =>'Ref Dig',
            'is_active' => 1,
            ],
            ['libelle'=> 'Dev data',
            'description' =>'Dev data',
            'is_active' => 1,
            ]
        ]);
    }
}
