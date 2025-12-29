<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Product;

class OrdersTableSeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/data/orders.json'));
        $ordersData = json_decode($json, true);

        foreach ($ordersData as $orderData) {
            $customer = Customer::where('email', $orderData['customer_email'])->first();

            if (!$customer) continue;

            $order = Order::create([
                'customer_id' => $customer->id,
                'order_number' => $orderData['order_number'],
                'status' => $orderData['status'],
                'total_amount' => $orderData['total_amount'],
                'tax_amount' => $orderData['tax'],
                'discount' => $orderData['discount'],
            ]);

            // Create order items
            
        }
    }
}
