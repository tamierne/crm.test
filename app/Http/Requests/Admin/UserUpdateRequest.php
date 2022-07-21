<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string:30',
            'avatar' => 'image|max:1024',
            'email' => 'required|email|unique:users,email,'.$this->user->id,
            // 'password' => 'required|string:20|min:10',
            // 'confirm-password' => 'required|same:password',
        ];
    }
}
