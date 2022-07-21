<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends BaseController
{
    public function index()
    {
        return ProjectResource::collection(Project::paginate(10));
    }

    public function show(Project $project)
    {
        return new ProjectResource($project);
    }
}
