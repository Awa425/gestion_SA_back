<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Apprenant;

class ApprenantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Apprenant::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'matricule'=>'P'.random_int(1,5).'DD_2023'.fake()->numerify('########################'),
            'genre'=>fake()->randomElement(['Feminin','Masculin']),
            'user_id'=>1,
            'cni'=>fake()->numerify('########################'),
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'email' => $this->faker->safeEmail,
            'password' => '$2y$10$ZAlv0NoSE5laQTtxa5LCxuC5AjHiYKzPSUbxyI3X5iqE6JD.Tq20u',
            'date_naissance' => $this->faker->date(),
            'lieu_naissance' => $this->faker->words(2,true),
            'is_active' => $this->faker->boolean,
        ];
    }
}
