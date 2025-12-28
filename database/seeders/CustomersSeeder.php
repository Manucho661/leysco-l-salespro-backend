<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/data/customers.json'));
        $customers = json_decode($json, true);

        foreach ($customers as $customer) {
            DB::table('customers')->insert([
                'name' => $customer['name'],
                'type' => $customer['type'],
                'category' => $customer['category'],
                'contact_person' => $customer['contact_person'],
                'phone' => $customer['phone'],
                'email' => $customer['email'],
                'tax_id' => $customer['tax_id'],
                'payment_terms' => $customer['payment_terms'],
                'credit_limit' => $customer['credit_limit'],
                'current_balance' => $customer['current_balance'],
                'latitude' => $customer['latitude'],
                'longitude' => $customer['longitude'],
                'address' => $customer['address'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
