<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $roles = [
            ['name' => 'Sales Manager', 'description' => 'Manages all sales activities and approvals'],
            ['name' => 'Sales Representative', 'description' => 'Handles individual customer sales'],
            ['name' => 'Admin', 'description' => 'Full access to the system'],
        ];

        DB::table('roles')->insert($roles);
    }
}
