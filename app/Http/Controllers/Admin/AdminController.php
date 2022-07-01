<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\TaskRepository;
use Illuminate\Http\Request;

class AdminController extends BaseController
{
    private TaskRepository $taskRepository;
    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function index()
    {
        $tasks = $this->taskRepository->getCurrentUserTasks();
        return view('admin.index', compact('tasks'));
    }
}
