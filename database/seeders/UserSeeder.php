<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

// ایجاد کاربر root
User::create([
    'name' => 'Root User',
    'email' => 'root@example.com',
    'password' => bcrypt('password'), // رمز عبور
])->assignRole('admin'); // انتساب نقش admin
    }
}
