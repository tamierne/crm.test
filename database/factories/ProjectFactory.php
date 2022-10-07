<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->realText(45),
            'description' => $this->faker->realText(200),
            'deadline' => $this->faker->dateTimeBetween('+15 days', '+2 months'),
            'user_id' => $this->faker->numberBetween(2, 13),
            'client_id' => $this->faker->numberBetween(1, 15),
            'status_id' => $this->faker->numberBetween(1, 5),
            'created_at' => $this->faker->dateTimeBetween('-5 months', '-1 week'),
            'updated_at' => $this->faker->dateTimeBetween('-2 months', '-1 day'),
        ];
    }
}
