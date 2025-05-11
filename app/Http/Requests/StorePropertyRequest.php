<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
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
        $currentYear = now()->year;
        return [
            'category_id'      => 'required|exists:categories,id',
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'location'         => 'required|string|max:255',
            'rent_price'       => 'required|numeric|min:0|max:99999999.99',
            'purchase_price'   => 'required|numeric|min:0|max:99999999.99',
            'old_rent_price'   => 'nullable|numeric|min:0|max:99999999.99',
            'old_purchase_price' => 'nullable|numeric|min:0|max:99999999.99',
            'bedrooms'         => 'required|integer|min:0',
            'bathrooms'        => 'required|integer|min:0',
            'garage'           => 'required|integer|min:0',
            'stories'          => 'required|integer|min:0',
            'lot_area'         => 'required|numeric|min:0',
            'floor_area'       => 'required|numeric|min:0',
            'year_built'       => "required|integer|digits:4|min:1970|max:{$currentYear}",
            'is_water'         => 'required|boolean',
            'is_new_roofing'   => 'required|boolean',
            'is_luggage'       => 'required|boolean',
            'latitude'         => 'required|numeric|between:-90,90',
            'longitude'        => 'required|numeric|between:-180,180',
            'main_image'       => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'other_images'     => 'required|array',
            'other_images.*'   => 'image|mimes:jpg,jpeg,png|max:2048',
            'amenities' => 'required|array',
            'amenities.*' => 'exists:amenities,id',
        ];
    }
    /**
     * Custom error messages for the validation.
     *
     * @return array
     */
    public function messages(): array
    {
        $currentYear = now()->year;

        return [
            'category_id.required' => 'Category is required.',
            'category_id.exists'   => 'The selected category is invalid.',
            'title.required'       => 'Please provide a title for the property.',
            'title.string'         => 'The title must be a text value.',
            'title.max'            => 'The title may not exceed 255 characters.',
            'description.required' => 'Please provide a description for the property.',
            'description.string'   => 'The description must be a text value.',
            'location.required'    => 'Location is required.',
            'location.string'      => 'Location must be a text value.',
            'location.max'         => 'Location may not exceed 255 characters.',
            'rent_price.required'     => 'Rent price is required.',
            'rent_price.numeric'      => 'Rent price must be a number.',
            'rent_price.min'          => 'Rent price must be at least 0.',
            'purchase_price.required' => 'Purchase price is required.',
            'purchase_price.numeric'  => 'Purchase price must be a number.',
            'purchase_price.min'      => 'Purchase price must be at least 0.',
            'old_rent_price.numeric'  => 'Old rent price must be a number.',
            'old_rent_price.min'      => 'Old rent price must be at least 0.',
            'old_purchase_price.numeric' => 'Old purchase price must be a number.',
            'old_purchase_price.min'     => 'Old purchase price must be at least 0.',
            'rent_price.max' => 'The rent price may not exceed 99,999,999.99.',
            'purchase_price.max' => 'The purchase price may not exceed 99,999,999.99.',
            'old_rent_price.max' => 'The old rent price may not exceed 99,999,999.99.',
            'old_purchase_price.max' => 'The old purchase price may not exceed 99,999,999.99.',
            'bedrooms.required'    => 'Bedrooms count is required.',
            'bedrooms.integer'     => 'Bedrooms must be a whole number.',
            'bedrooms.min'         => 'Bedrooms must be at least 0.',
            'bathrooms.required'   => 'Bathrooms count is required.',
            'bathrooms.integer'    => 'Bathrooms must be a whole number.',
            'bathrooms.min'        => 'Bathrooms must be at least 0.',
            'garage.required'      => 'Garage count is required.',
            'garage.integer'       => 'Garage must be a whole number.',
            'garage.min'           => 'Garage must be at least 0.',
            'stories.required'     => 'Stories count is required.',
            'stories.integer'      => 'Stories must be a whole number.',
            'stories.min'          => 'Stories must be at least 0.',
            'lot_area.required'    => 'Lot area is required.',
            'lot_area.numeric'     => 'Lot area must be a number.',
            'lot_area.min'         => 'Lot area must be at least 0.',
            'floor_area.required'  => 'Floor area is required.',
            'floor_area.numeric'   => 'Floor area must be a number.',
            'floor_area.min'       => 'Floor area must be at least 0.',
            'year_built.required'  => 'Year built is required.',
            'year_built.integer'   => 'Year built must be a whole number.',
            'year_built.digits'    => 'Year built must be a 4-digit year.',
            "year_built.min"       => "Year built cannot be before 1970.",
            "year_built.max"       => "Year built cannot be after {$currentYear}.",
            'is_water.required'       => 'Please indicate whether water supply is available.',
            'is_water.boolean'        => 'Invalid value for water supply.',
            'is_new_roofing.required' => 'Please indicate roofing status.',
            'is_new_roofing.boolean'  => 'Invalid value for roofing status.',
            'is_luggage.required'     => 'Please indicate whether luggage storage is available.',
            'is_luggage.boolean'      => 'Invalid value for luggage storage.',
            'latitude.required'     => 'Latitude is required.',
            'latitude.numeric'      => 'Latitude must be a number.',
            'latitude.between'      => 'Latitude must be between –90 and 90.',
            'longitude.required'    => 'Longitude is required.',
            'longitude.numeric'     => 'Longitude must be a number.',
            'longitude.between'     => 'Longitude must be between –180 and 180.',
            'main_image.image'      => 'The main image must be an image file.',
            'main_image.mimes'      => 'Main image must be a jpg, jpeg, or png.',
            'main_image.max'        => 'Main image may not be larger than 2 MB.',
            'other_images.array'    => 'Other images must be provided as an array.',
            'other_images.*.image'  => 'Each additional image must be an image file.',
            'other_images.*.mimes'  => 'Additional images must be jpg, jpeg, or png.',
            'other_images.*.max'    => 'Each additional image may not exceed 2 MB.',
        ];
    }
}
