<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@elibrary.test',
            ],
            [
                'name' => 'Administrator',
                'password' => 'password123',
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            [
                'email' => 'siswa@elibrary.test',
            ],
            [
                'name' => 'Siswa Demo',
                'password' => 'password123',
                'role' => 'siswa',
            ]
        );
    }
}