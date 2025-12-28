<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/data/users.json'));

        $users = json_decode($json, true);

        foreach ($users as $userData) {

            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'username'   => $userData['username'],
                    'first_name' => $userData['first_name'],
                    'last_name'  => $userData['last_name'],

                    'role' => $userData['role'],

                    'permissions' => isset($userData['permissions']) ? json_encode($userData['permissions']) : null,

                    'status' => $userData['status'] ?? 'active',

                    'password' => Hash::make($userData['password']),
                ]
            );
        }
    }
}
