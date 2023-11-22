<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Promo;

class PromoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Promo::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {   $i=1;
        return [
            'libelle' => 'Promo'.$i,
            'date_debut' => $this->faker->date(),
            'date_fin_prevue' => $this->faker->date(),
            'date_fin_reel' => $this->faker->date(),
            'is_active' => $this->faker->boolean,
        ];
    }
}
