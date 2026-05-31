<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        User::create([
            'name' => 'Quản Trị Viên Cao Cấp',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'), 
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'Nguyễn Văn A',
            'email' => 'usera@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
        ]);
        User::create([
            'name' => 'Trần Thị B',
            'email' => 'userb@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
        ]);
    }
}