<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Residential',
                'description' => 'Properties designed for living purposes such as houses, apartments, and condominiums.'
            ],
            [
                'name'        => 'Commercial',
                'description' => 'Properties used for business activities including offices, retail stores, and shopping centers.'
            ],
            [
                'name'        => 'Industrial',
                'description' => 'Properties intended for manufacturing, warehousing, and distribution operations.'
            ],
            [
                'name'        => 'Land',
                'description' => 'Raw land for development, agricultural use, or investment purposes.'
            ],
            [
                'name'        => 'Mixed-Use',
                'description' => 'Properties that combine residential, commercial, and sometimes industrial uses.'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
