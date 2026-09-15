<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ug.edu.gh'],
            [
                'firstname' => 'System',
                'surname' => 'Administrator',
                'password' => Hash::make('UgNimda@2026'),
                'role' => 'admin',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'superadmin@ug.edu.gh'],
            [
                'firstname' => 'Super',
                'surname' => 'Administrator',
                'personnel_id' => 'UG-SPA-2026-0001',
                'password' => Hash::make('UgReddok@2026'),
                'role' => 'super_admin',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
