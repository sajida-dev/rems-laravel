<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHiringRequestRequest extends FormRequest
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
            'request_type' => ['required', 'in:rent,buy,sell'],
            'agent_id' => ['required', 'exists:agents,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'location' => ['required', 'string', 'max:255'],
            'min_budget' => ['nullable', 'numeric', 'min:0'],
            'max_budget' => ['nullable', 'numeric', 'min:0', 'gte:min_budget'],
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'requirements' => ['required', 'string'],
        ];
    }
    public function messages(): array
    {
        return [
            'category_id.exists' => 'The selected category does not exist.',
            'max_budget.gte' => 'Maximum budget must be greater than or equal to minimum budget.',
        ];
    }
}
