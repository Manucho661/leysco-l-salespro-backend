<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = [
            ['name' => 'Engine Oils', 'description' => 'Oils for vehicle engines'],
            ['name' => 'Lubricants', 'description' => 'Various lubricants for machinery'],
            ['name' => 'Additives', 'description' => 'Engine and fuel additives'],
        ];

        DB::table('categories')->insert($categories);
    }
}
