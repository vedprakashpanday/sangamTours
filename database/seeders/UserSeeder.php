<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;



class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // UserSeeder.php
User::create([
    'name' => 'Admin',
    'email' => 'admin@jankivilla.com',
    'password' => Hash::make('password123'),
    'role' => 'admin'
]);
    }
}
