<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Apprenant;
use App\Models\Promo;
use App\Models\Promo_Referentiel_Apprenant;
use App\Models\Referentiel;

class PromoReferentielApprenantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PromoReferentielApprenant::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'promo_id' => Promo::factory(),
            'referentiel_id' => Referentiel::factory(),
            'apprenant_id' => Apprenant::factory(),
        ];
    }
}
