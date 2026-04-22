<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'm.fofana@ivoireindustriemag.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('FoF@2026'),
                'role'     => 'super_admin',
                'slug'     => 'super-admin',
            ]
        );
    }
}
