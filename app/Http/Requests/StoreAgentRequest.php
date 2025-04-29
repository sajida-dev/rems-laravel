<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAgentRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'licence_no' => 'nullable|numeric|unique:agents,licence_no',
            'agency' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20',
            'experience' => 'required|integer|min:0',
            'bio' => 'nullable|string',
            'status' => 'nullable|boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ];
    }
}
