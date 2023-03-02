<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Presence;

class PresenceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Presence::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'date_heure_arriver' => $this->faker->dateTime(),
        ];
    }
}
