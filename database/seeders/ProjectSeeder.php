<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use Illuminate\Foundation\Testing\WithFaker;

class ProjectSeeder extends Seeder
{
    use WithoutModelEvents, WithFaker;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::factory()
            ->count(25)
            ->create()
            ->each(function ($project) {
                $status = Status::inRandomOrder()
                    ->first();
                $project->status($status)->save();
            });
    }
}
