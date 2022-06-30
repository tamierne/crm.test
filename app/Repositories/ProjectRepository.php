<?php

namespace App\Repositories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\MainRepository;

class ProjectRepository extends MainRepository
{
    public function getAllProjects()
    {
        return Project::all(['id', 'title']);
    }

    public function getProjectById($id)
    {
        return Project::find($id);
    }
}
