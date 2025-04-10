<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'              => 'Admin User',
            'username'          => 'admin',
            'email'             => 'admin@example.com',
            'password'          => Hash::make('admin'),
            'role'              => 'admin',
            'contact'           => 1234567890,
        ]);
    }
}
