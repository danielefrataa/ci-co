<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Front Office User',
            'email' => 'frontoffice@example.com',
            'password' => Hash::make('password123'),
            'role' => 'frontoffice',
        ]);

        User::create([
            'name' => 'Marketing User',
            'email' => 'marketing@example.com',
            'password' => Hash::make('password123'),
            'role' => 'marketing',
        ]);

        User::create([
            'name' => 'Produksi User',
            'email' => 'produksi@example.com',
            'password' => Hash::make('password123'),
            'role' => 'produksi',
        ]);

        User::create([
            'name' => 'IT User',
            'email' => 'it@example.com',
            'password' => Hash::make('password123'),
            'role' => 'IT',
        ]);
    }
}
