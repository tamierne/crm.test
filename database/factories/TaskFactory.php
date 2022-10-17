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
            'title' => $this->faker->realText(45),
            'description' => $this->faker->realText(200),
            'deadline' => $this->faker->dateTimeBetween('+10 days', '+20 days'),
            'project_id' => $this->faker->numberBetween(1, 10),
            'user_id' => $this->faker->numberBetween(2, 10),
//            'status_id' => $this->faker->numberBetween(1, 5),
            'created_at' => $this->faker->dateTimeBetween('-2 months', '-1 week'),
            'updated_at' => $this->faker->dateTimeBetween('-1 week', '-1 day'),
        ];
    }
}
