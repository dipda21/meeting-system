<?php
// database/seeders/AdminUserSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@banksulselbar.co.id',
            'password' => Hash::make('password'),
            'position' => 'System Administrator',
            'department' => 'IT Department',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Pegawai Test',
            'email' => 'pegawai@banksulselbar.co.id',
            'password' => Hash::make('password'),
            'position' => 'Staff',
            'department' => 'General Affairs',
            'role' => 'pegawai',
            'email_verified_at' => now(),
        ]);
    }
}