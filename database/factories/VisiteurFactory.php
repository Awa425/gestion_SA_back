<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Visiteur;

class VisiteurFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Visiteur::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nom' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'prenom' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'INE' => $this->faker->numberBetween(-100000, 100000),
            'motif' => $this->faker->regexify('[A-Za-z0-9]{255}'),
        ];
    }
}
