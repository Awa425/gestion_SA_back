<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Promo::insert([
            ['libelle'=> 'Promo 1',
                'date_debut'=>'2023-12-10',
                'date_fin_prevue'=>'2023-12-29',
                'date_fin_reel'=>'2023-12-12',
                'user_id'=>1,
                'is_ongoing'=>0,
                'is_active'=>0
            ],
            ['libelle'=> 'Promo 2',
            'date_debut'=>'2023-12-10',
                'date_fin_prevue'=>'2023-12-29',
                'date_fin_reel'=>'2023-12-12',
                'user_id'=>1,
                'is_ongoing'=>0,
                'is_active'=>0
            ],
            ['libelle'=> 'Promo 3',
            'date_debut'=>'2023-12-10',
                'date_fin_prevue'=>'2023-12-29',
                'date_fin_reel'=>'2023-12-12',
                'user_id'=>1,
                'is_ongoing'=>0,
                'is_active'=>0
            ],
            ['libelle'=> 'Promo 4',
            'date_debut'=>'2023-12-10',
                'date_fin_prevue'=>'2023-12-29',
                'date_fin_reel'=>'2023-12-12',
                'user_id'=>1,
                'is_ongoing'=>0,
                'is_active'=>0
            ],
            ['libelle'=> 'Promo 5',
            'date_debut'=>'2023-12-10',
            'date_fin_prevue'=>'2023-12-29',
            'date_fin_reel'=>'2023-12-12',
            'user_id'=>1,
            'is_ongoing'=>1,
            'is_active'=>1
            ],
        ]);
    }
}
