<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ProjectUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('project_update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string:50|unique:projects,title',
            'description' => 'required|string:300',
            'deadline' => 'required|date|after:today',
            'user_id' => 'required|exists:users,id',
            'client_id' => 'required|exists:clients,id',
            'status_id' => 'required|exists:statuses,id',
        ];
    }
}
