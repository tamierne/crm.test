<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\TaskRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class AdminController extends BaseController
{
    private TaskRepository $taskRepository;
    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @return Illuminate\View\View
     */
    public function index(): View
    {
        $tasks = $this->taskRepository->getCurrentUserTasks();
        return view('admin.index', compact('tasks'));
    }

    public function activity()
    {
        $activities = Activity::simplePaginate('50');
        return view('admin.activity', compact('activities'));
    }
}
