<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Status>
 */
class StatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // return [
        //     'status_id' => $this->faker->numberBetween(1, 4),
        //     'statusable_id' => $this->faker->numberBetween(1, 10),
        //     'statusable_type' => $this->faker->randomElement(['App\Models\Project', 'App\Models\Tasks']),
        // ];
    }
}
