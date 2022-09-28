<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;

class CounterService
{
    public function getUserAllProjectsCount(User $user)
    {
        return count($user->projects());
    }

    public function getUserActiveProjectsCount(User $user)
    {
        $projects = Project::where('user_id', '=', $user->id)
            ->where('status_id', '=', Status::STATUS_PROCESSING)
            ->count();

        return $projects;
    }

    public function getUserAllTasksCount(User $user)
    {
        return count($user->tasks());
    }

    public function getUserActiveTasksCount(User $user)
    {
        $tasks = Task::where('user_id', '=', $user->id)
            ->where('status_id', '=', Status::STATUS_PROCESSING)
            ->count();

        return $tasks;
    }

    public function getUserAllParsersCount(User $user)
    {
        return count($user->tasks());
    }
}
