<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Apprenant;
use App\Models\User;

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
            'nom' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'prenom' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'email' => $this->faker->safeEmail,
            'password' => $this->faker->password,
            'date_naissance' => $this->faker->date(),
            'lieu_naissance' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'user_id' => User::factory(),
            'is_active' => $this->faker->boolean,
        ];
    }
}
