<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResourse;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return ProjectResourse::collection(Project::paginate(10));
    }

    public function show(Project $project)
    {
        return new ProjectResourse($project);
    }
}
