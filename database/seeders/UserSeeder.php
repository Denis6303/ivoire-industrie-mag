<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ivoireindustriemag.ci'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Admin@2025'),
                'role' => 'super_admin',
                'slug' => 'super-admin',
            ]
        );
    }
}
