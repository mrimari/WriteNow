<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'is_admin' => true,
            'avatar' => 'default-avatar.png',
            'vk_link' => 'https://vk.com/id1234567890',
            'tg_link' => 'https://t.me/id1234567890',
            'about' => 'I am admin',
        ]);
    }
}
