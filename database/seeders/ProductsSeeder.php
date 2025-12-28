<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $json = file_get_contents(database_path('seeders/data/products.json'));
        $products = json_decode($json, true);

        foreach ($products as $product) {
            DB::table('products')->insert([
                'sku' => $product['sku'],
                'name' => $product['name'],
                'category' => $product['category'],
                'subcategory' => $product['subcategory'],
                'description' => $product['description'],
                'price' => $product['price'],
                'tax_rate' => $product['tax_rate'],
                'unit' => $product['unit'],
                'packaging' => $product['packaging'],
                'min_order_quantity' => $product['min_order_quantity'],
                'reorder_level' => $product['reorder_level'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
