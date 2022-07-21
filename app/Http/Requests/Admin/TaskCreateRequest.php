<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TaskCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->authorize('task_store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|min:5|string:50|unique:tasks,title',
            'description' => 'required|min:20|string:300',
            'deadline' => 'required|date|after:today',
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
            'status_id' => 'required|exists:statuses,id',
        ];
    }
}
