<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Ensure test user exists (idempotent)
        \App\Models\User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );

        // Admin user for BicolVax (create or update)
        \App\Models\User::updateOrCreate(
            ['email' => 'bicolvaxclinic@gmail.com'],
            [
                'name' => 'BicolVax Clinic',
                'password' => \Illuminate\Support\Facades\Hash::make('Admin_123'),
                'is_admin' => true,
            ]
        );
    }
}
