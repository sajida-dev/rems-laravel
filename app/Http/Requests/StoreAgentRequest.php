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
            'licence_no' => 'required|numeric|unique:agents,licence_no',
            'agency' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'experience' => 'required|integer|min:0',
            'bio' => 'required|string',
            'status' => 'nullable|boolean',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'avatar'       => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
