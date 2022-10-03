<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(2, 13),
            'name' => $this->faker->company,
            'VAT' => $this->faker->randomNumber(8, false),
            'address' => $this->faker->address,
            'created_at' => $this->faker->dateTimeBetween('-5 months', '-1 week'),
            'updated_at' => $this->faker->dateTimeBetween('-2 months', '-1 day'),
        ];
    }
}
