<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
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
            'deadline' => $this->faker->dateTimeThisMonth('+25 days'),
            'project_id' => $this->faker->numberBetween(1, 25),
            'user_id' => $this->faker->numberBetween(2, 13),
            'status_id' => $this->faker->numberBetween(1, 5),
            'created_at' => $this->faker->dateTimeBetween('-2 months', '-1 week'),
            'updated_at' => $this->faker->dateTimeBetween('-1 week', '-1 day'),
        ];
    }
}
