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
            'title' => $this->faker->sentence(5),
            'description' => $this->faker->realText(200),
            'deadline' => $this->faker->dateTimeThisMonth('+2 months'),
            'user_id' => $this->faker->numberBetween(1, 10),
            'client_id' => $this->faker->numberBetween(1, 10),
            'status_id' => $this->faker->numberBetween(1, 4),
            'created_at' => $this->faker->dateTimeBetween('-3 months', '-1 week'),
            'updated_at' => $this->faker->dateTimeBetween('-1 week', '-1 day'),
        ];
    }
}
