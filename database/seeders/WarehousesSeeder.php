<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class WarehousesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $json = file_get_contents(database_path('seeders/data/warehouses.json'));
        $warehouses = json_decode($json, true);

        foreach ($warehouses as $warehouse) {
            DB::table('warehouses')->insert([
                'code' => $warehouse['code'],
                'name' => $warehouse['name'],
                'type' => $warehouse['type'],
                'address' => $warehouse['address'],
                'manager_email' => $warehouse['manager_email'],
                'phone' => $warehouse['phone'],
                'capacity' => $warehouse['capacity'],
                'latitude' => $warehouse['latitude'],
                'longitude' => $warehouse['longitude'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

    }
}
