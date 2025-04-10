<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = [
            [
                'name'        => 'Swimming Pool',
                'description' => 'Outdoor or indoor pool facilities for residents to relax and exercise.'
            ],
            [
                'name'        => 'Gym',
                'description' => 'Well-equipped fitness center with modern exercise machines and weights.'
            ],
            [
                'name'        => 'Parking',
                'description' => 'Secure parking spaces available for residents and visitors.'
            ],
            [
                'name'        => 'Security',
                'description' => '24/7 security services including CCTV surveillance and on-site personnel.'
            ],
            [
                'name'        => 'Playground',
                'description' => 'Dedicated play areas designed for children and families.'
            ],
            [
                'name'        => 'Common Area',
                'description' => 'Shared spaces such as lounges or community rooms for social gatherings.'
            ],
            [
                'name'        => 'Laundry Room',
                'description' => 'Facilities equipped with washers and dryers available for residents.'
            ],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create($amenity);
        }
    }
}
