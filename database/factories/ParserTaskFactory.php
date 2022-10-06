<?php

namespace Database\Factories;

use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ParserTask>
 */
class ParserTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'url' => $this->faker->url,
            'user_id' => $this->faker->numberBetween(2, 13),
            'status_id' => Status::STATUS_QUEUED,
        ];
    }
}
