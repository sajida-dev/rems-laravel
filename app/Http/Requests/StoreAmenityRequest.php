<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAmenityRequest extends FormRequest
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
            'amenityName' => ['required', 'string', 'max:100'],
            'amenityDescription' => ['required', 'string', 'max:500'],
        ];
    }

    /**
     * Custom forbidden response for unauthorized users
     */
    public function forbiddenResponse()
    {
        return response()->json([
            'message' => 'You are not authorized to perform this action.'
        ], 403);
    }
}
