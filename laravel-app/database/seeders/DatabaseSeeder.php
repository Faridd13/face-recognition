<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Administrator',
            'email' => 'admin123@gmail.com',
            'password' => bcrypt('adminnyaganteng'),
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'Guru Farid',
            'email' => 'gurufarid@gmail.com',
            'password' => bcrypt('faridtampan'),
            'role' => 'guru',
        ]);

        \App\Models\Condition::create([
            'light_condition' => 'terang',
            'face_angle' => 'frontal',
            'distance_condition' => 'dekat',
        ]);

        \App\Models\Condition::create([
            'light_condition' => 'terang',
            'face_angle' => 'frontal',
            'distance_condition' => 'jauh',
        ]);

        \App\Models\Condition::create([
            'light_condition' => 'terang',
            'face_angle' => 'nonfrontal',
            'distance_condition' => 'dekat',
        ]);

        \App\Models\Condition::create([
            'light_condition' => 'terang',
            'face_angle' => 'nonfrontal',
            'distance_condition' => 'jauh',
        ]);

        \App\Models\Condition::create([
            'light_condition' => 'redup',
            'face_angle' => 'frontal',
            'distance_condition' => 'dekat',
        ]);

        \App\Models\Condition::create([
            'light_condition' => 'redup',
            'face_angle' => 'nonfrontal',
            'distance_condition' => 'jauh',
        ]);
    }
}
