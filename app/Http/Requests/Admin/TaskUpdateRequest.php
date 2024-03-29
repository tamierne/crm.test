<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class TaskUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('task_update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string:50|unique:tasks,title,'.$this->task->id,
            'description' => 'required|string:300',
            'deadline' => 'required|date|after:today',
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
            'status_id' => 'required|exists:statuses,id',
        ];
    }
}
