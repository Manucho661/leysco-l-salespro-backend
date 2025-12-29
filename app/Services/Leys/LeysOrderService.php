<?php

namespace App\Services\Leys;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\StockReservation;
use App\Helpers\LeyscoHelpers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Jobs\OrderConfirmationEmail;

class LeysOrderService
{
    public function createOrder(array $data)
    {
        return DB::transaction(function() use ($data) {
            $customer = Customer::findOrFail($data['customer_id']);
            $itemsData = $data['items'];

            // Step 1: Validate Credit Limit
            $subtotal = $this->calculateSubtotal($itemsData);
            if(($customer->credit_limit - $customer->current_balance) < $subtotal) {
                throw new \Exception('Customer credit limit exceeded.');
            }

            $reservations = [];
            $discountTotal = 0;
            $taxTotal = 0;
            $grandTotal = 0;

            // Step 2-5: Stock, discount, tax
            foreach($itemsData as &$item) {
                $product = Product::findOrFail($item['product_id']);

                // Check stock
                $available = $product->inventory()->sum('quantity') 
                             - $product->stockReservations()->sum('quantity');
                if($item['quantity'] > $available) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                // Reserve stock (temporary)
                $reservations[] = StockReservation::create([
                    'order_id' => null,
                    'product_id' => $product->id,
                    'warehouse_id' => $item['warehouse_id'] ?? 1, // default warehouse
                    'quantity' => $item['quantity'],
                    'reserved_until' => Carbon::now()->addMinutes(30)
                ]);

                // Calculate line total
                $lineTotal = $product->price * $item['quantity'];

                // Apply discount
                if(!empty($item['discount_type'])){
                    if($item['discount_type'] === 'percent'){
                        $lineTotal -= ($lineTotal * $item['discount_value'] / 100);
                    } else {
                        $lineTotal -= $item['discount_value'];
                    }
                }
                $discountTotal += ($product->price * $item['quantity']) - $lineTotal;

                // Apply tax
                $item['tax_value'] = LeyscoHelpers::calculateTax($lineTotal, $product->tax_rate);
                $taxTotal += $item['tax_value'];

                $item['line_total'] = $lineTotal;
                $grandTotal += $lineTotal + $item['tax_value'];
            }

            // Step 6: Create Order
            $order = Order::create([
                'customer_id' => $customer->id,
                'order_number' => LeyscoHelpers::generateOrderNumber(),
                'subtotal' => $subtotal,
                'discount_total' => $discountTotal,
                'tax_total' => $taxTotal,
                'grand_total' => $grandTotal,
                'status' => 'pending',
                'reserved_expires_at' => Carbon::now()->addMinutes(30)
            ]);

            // Step 7: Update Reservations
            foreach($reservations as $r) $r->update(['order_id' => $order->id]);

            // Step 8: Create Order Items
            foreach($itemsData as $item){
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => Product::find($item['product_id'])->price,
                    'discount_type' => $item['discount_type'] ?? null,
                    'discount_value' => $item['discount_value'] ?? 0,
                    'tax' => $item['tax_value'],
                    'line_total' => $item['line_total']
                ]);
            }

            // Step 9: Queue confirmation email
            OrderConfirmationEmail::dispatch($order);

            return $order;
        });
    }

    protected function calculateSubtotal($itemsData)
    {
        return array_sum(array_map(function($item){
            $product = Product::find($item['product_id']);
            return $product->price * $item['quantity'];
        }, $itemsData));
    }

    
}
