<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'ICT Super Administrator',
                'email' => 'admin@cmc.local',
                'password' => Hash::make('password'),
                'role' => User::ROLE_SUPER_ADMIN,
                'department' => 'IHOMP / Information & Communications Technology',
                'assigned_categories' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Citizen Charter Pricelist Encoder',
                'email' => 'encoder@cmc.local',
                'password' => Hash::make('password'),
                'role' => User::ROLE_PRICELIST_ENCODER,
                'department' => 'Billing & Citizen Charter Unit',
                'assigned_categories' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Public Information Officer',
                'email' => 'pio@cmc.local',
                'password' => Hash::make('password'),
                'role' => User::ROLE_PUBLIC_INFO_OFFICER,
                'department' => 'Public Information & Assistance Desk',
                'assigned_categories' => null,
                'is_active' => true,
            ],
        ];

        foreach ($users as $data) {
            User::query()->updateOrCreate(
                ['email' => $data['email']],
                $data,
            );
        }
    }
}
