<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin default
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_verified' => true,
            ]
        );

        // Staff default
        User::updateOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'is_verified' => true,
            ]
        );

        // User biasa untuk testing
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_verified' => true,
            ]
        );

        // User Demo untuk SRS Module 2 (Owner & Member)
        User::updateOrCreate(
            ['email' => 'user1@example.com'],
            [
                'name' => 'User Satu (Owner Demo)',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'user2@example.com'],
            [
                'name' => 'User Dua (Member Demo)',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
