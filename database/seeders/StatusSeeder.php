<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = Project::all();
        $tasks = Task::all();

        foreach ($projects as $project)
        {
            $status = Status::inRandomOrder()
                ->first();
//            dd($status->id);
            $project->status()->attach($status->id);
        }

        foreach ($tasks as $task)
        {
            $status = Status::inRandomOrder()
                ->first();
            $task->status()->attach($status->id);
        }
    }

}
