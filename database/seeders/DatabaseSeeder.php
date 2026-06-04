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
        $users = [
            [
                'name' => 'Quản Trị Viên Cao Cấp',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Nguyễn Văn A',
                'email' => 'usera@gmail.com',
                'role' => 'user',
            ],
            [
                'name' => 'Trần Thị B',
                'email' => 'userb@gmail.com',
                'role' => 'user',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                [
                    'email' => $userData['email']
                ],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('12345678'),
                ],
            );

            $user->forceFill(['role' => $userData['role']])->save();
        }
    }
}
