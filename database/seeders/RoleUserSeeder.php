<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleUserSeeder extends Seeder
{
    /**
     * Seed default users for each system role.
     */
    public function run(): void
    {
        $defaultPassword = 'Password123!';

        $users = [
            [
                'name' => 'System Admin',
                'email' => 'admin@midland.local',
                'role' => 'admin',
            ],
            [
                'name' => 'System Manager',
                'email' => 'manager@midland.local',
                'role' => 'manager',
            ],
            [
                'name' => 'System Marketing',
                'email' => 'marketing@midland.local',
                'role' => 'marketing',
            ],
            [
                'name' => 'System Documentation',
                'email' => 'documentation@midland.local',
                'role' => 'documentation',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'password' => Hash::make($defaultPassword),
                ]
            );
        }
    }
}
