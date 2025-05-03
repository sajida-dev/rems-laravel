<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAgentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->route('agent')->user_id)
            ],
            'licence_no' => [
                'required',
                'numeric',
                Rule::unique('agents', 'licence_no')->ignore($this->route('agent')->id),
            ],
            'agency' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20',
            'experience' => 'required|integer|min:0',
            'bio' => 'required|string',
            'status' => 'nullable|boolean',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ];
    }
}
