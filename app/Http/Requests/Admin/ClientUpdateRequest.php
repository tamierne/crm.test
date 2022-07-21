<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ClientUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->authorize('client_store');
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
            'VAT' => 'required|numeric:10|unique:clients,VAT,'.$this->client->id,
            'address' => 'required|string:100|min:10',
            'avatar' => 'image|max:1024',
        ];
    }
}
