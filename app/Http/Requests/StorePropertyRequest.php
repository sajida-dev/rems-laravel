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
        return [
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'location'         => 'required|string|max:255',
            'category_id'      => 'required|exists:categories,id',
            'type'            => 'required|in:rent,buy',
            'bedrooms'        => 'required|integer|min:0|max:100',
            'bathrooms'       => 'required|integer|min:0|max:100',
            'stories'         => 'required|integer|min:0|max:100',
            'lot_area'        => 'required|numeric|min:0|max:99999999.99',
            'floor_area'      => 'required|numeric|min:0|max:99999999.99',
            'year_built'      => 'required|integer|min:1800|max:' . (date('Y') + 1),
            'is_water'        => 'required|in:0,1',
            'is_new_roofing'  => 'required|in:0,1',
            'is_luggage'      => 'required|in:0,1',
            'garage'          => 'required|integer|min:0|max:100',
            'amenities'       => 'required|array',
            'amenities.*'     => 'exists:amenities,id',
            'main_image'      => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'other_images'    => 'required|array|min:1',
            'other_images.*'  => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'rent_price'      => 'required_if:type,rent|nullable|numeric|min:0|max:99999999.99',
            'purchase_price'  => 'required_if:type,buy|nullable|numeric|min:0|max:99999999.99',
            'latitude'        => 'required|numeric|between:-90,90',
            'longitude'       => 'required|numeric|between:-180,180',
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
            'type.required'        => 'Please select a property type.',
            'type.in'             => 'Property type must be either rent or buy.',
            'rent_price.required_if' => 'Rent price is required for rental properties.',
            'rent_price.numeric'     => 'Rent price must be a number.',
            'rent_price.min'         => 'Rent price must be at least 0.',
            'rent_price.max'         => 'The rent price may not exceed 99,999,999.99.',
            'purchase_price.required_if' => 'Purchase price is required for properties for sale.',
            'purchase_price.numeric'    => 'Purchase price must be a number.',
            'purchase_price.min'        => 'Purchase price must be at least 0.',
            'purchase_price.max'        => 'The purchase price may not exceed 99,999,999.99.',
            'bedrooms.required'    => 'Bedrooms count is required.',
            'bedrooms.integer'     => 'Bedrooms must be a whole number.',
            'bedrooms.min'         => 'Bedrooms must be at least 0.',
            'bedrooms.max'         => 'Bedrooms cannot exceed 100.',
            'bathrooms.required'   => 'Bathrooms count is required.',
            'bathrooms.integer'    => 'Bathrooms must be a whole number.',
            'bathrooms.min'        => 'Bathrooms must be at least 0.',
            'bathrooms.max'        => 'Bathrooms cannot exceed 100.',
            'garage.required'      => 'Garage count is required.',
            'garage.integer'       => 'Garage must be a whole number.',
            'garage.min'           => 'Garage must be at least 0.',
            'garage.max'           => 'Garage cannot exceed 100.',
            'stories.required'     => 'Stories count is required.',
            'stories.integer'      => 'Stories must be a whole number.',
            'stories.min'          => 'Stories must be at least 0.',
            'stories.max'          => 'Stories cannot exceed 100.',
            'lot_area.required'    => 'Lot area is required.',
            'lot_area.numeric'     => 'Lot area must be a number.',
            'lot_area.min'         => 'Lot area must be at least 0.',
            'lot_area.max'         => 'Lot area cannot exceed 99,999,999.99.',
            'floor_area.required'  => 'Floor area is required.',
            'floor_area.numeric'   => 'Floor area must be a number.',
            'floor_area.min'       => 'Floor area must be at least 0.',
            'floor_area.max'       => 'Floor area cannot exceed 99,999,999.99.',
            'year_built.required'  => 'Year built is required.',
            'year_built.integer'   => 'Year built must be a whole number.',
            'year_built.min'       => 'Year built cannot be before 1800.',
            'year_built.max'       => "Year built cannot be after {$currentYear}.",
            'is_water.required'    => 'Please indicate whether water supply is available.',
            'is_water.in'          => 'Invalid value for water supply.',
            'is_new_roofing.required' => 'Please indicate roofing status.',
            'is_new_roofing.in'    => 'Invalid value for roofing status.',
            'is_luggage.required'  => 'Please indicate whether luggage storage is available.',
            'is_luggage.in'        => 'Invalid value for luggage storage.',
            'amenities.required'   => 'Please select at least one amenity.',
            'amenities.array'      => 'Amenities must be selected as a list.',
            'amenities.*.exists'   => 'One or more selected amenities are invalid.',
            'main_image.required'  => 'Main image is required.',
            'main_image.image'     => 'The main image must be an image file.',
            'main_image.mimes'     => 'Main image must be a jpg, jpeg, png, or gif.',
            'main_image.max'       => 'Main image may not be larger than 2 MB.',
            'other_images.required' => 'At least one additional image is required.',
            'other_images.array'   => 'Additional images must be provided as a list.',
            'other_images.min'     => 'At least one additional image is required.',
            'other_images.*.image' => 'Each additional image must be an image file.',
            'other_images.*.mimes' => 'Additional images must be jpg, jpeg, png, or gif.',
            'other_images.*.max'   => 'Each additional image may not exceed 2 MB.',
            'latitude.required'    => 'Latitude is required.',
            'latitude.numeric'     => 'Latitude must be a number.',
            'latitude.between'     => 'Latitude must be between -90 and 90.',
            'longitude.required'   => 'Longitude is required.',
            'longitude.numeric'    => 'Longitude must be a number.',
            'longitude.between'    => 'Longitude must be between -180 and 180.',
        ];
    }
}
