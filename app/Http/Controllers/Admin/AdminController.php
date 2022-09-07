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
        $notifications = auth()->user()->notifications;
        return view('admin.index',
        [
            'tasks' => $tasks,
            'notifications' => $notifications,
        ]);
    }

    public function activity()
    {
        $activities = Activity::simplePaginate('50');
        return view('admin.activity', compact('activities'));
    }
}
