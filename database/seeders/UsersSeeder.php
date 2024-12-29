<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Kepala Dinas',
            'email' => 'kadin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'kadin',
        ]);

        // User Kabid
        User::create([
            'name' => 'Kepala Bidang Keuangan',
            'email' => 'kabid@example.com',
            'password' => Hash::make('password123'),
            'role' => 'kabid',
        ]);
    }
}
