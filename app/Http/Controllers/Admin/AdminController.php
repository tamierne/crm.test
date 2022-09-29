<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notification;
use App\Repositories\TaskRepository;
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

        auth()->user()->hasRole('super-admin')
            ? $notifications = Notification::adminUnread()->get()
            : $notifications = auth()->user()->unreadNotifications;

        return view('admin.index',
        [
            'tasks' => $tasks,
            'notifications' => $notifications,
        ]);
    }

    public function activity()
    {
        $activities = Activity::orderByDesc('created_at')->simplePaginate('50');

        return view('admin.activity', compact('activities'));
    }

    public function mark()
    {
        auth()->user()->hasRole('super-admin')
            ? Notification::markAllAsAdminRead()
            : auth()->user()->unreadNotifications->markAsRead();

        return back();
    }
}
