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
        // 1. Administrator Account
        User::updateOrCreate(
            ['email' => 'admin@worthyacosta.ph'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 2. Assistant Account
        User::updateOrCreate(
            ['email' => 'assistant@worthyacosta.ph'],
            [
                'name' => 'Assistant Officer',
                'username' => 'assistant',
                'password' => Hash::make('password'),
                'role' => 'assistant',
            ]
        );
    }
}
