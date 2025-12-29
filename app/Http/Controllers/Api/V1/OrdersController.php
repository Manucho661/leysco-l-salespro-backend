<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Services\Leys\LeysOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class OrdersController extends Controller
{
    protected LeysOrderService $orderService;

    public function __construct(LeysOrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Create a new order
     *
     * @param CreateOrderRequest $request
     * @return JsonResponse
     */

    // List orders
    public function index(Request $request): JsonResponse
    {
        $query = Order::query();

        // Optional filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Fetch only orders without items or products
        $orders = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $orders,
            'message' => 'Orders retrieved successfully'
        ]);
    }


    public function store(CreateOrderRequest $request): JsonResponse
    {
        try {
            $order = $this->orderService->createOrder($request->validated());

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Order created successfully.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Update order status
     *
     * @param UpdateOrderStatusRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateStatus(UpdateOrderStatusRequest $request, int $id): JsonResponse
    {
        $order = Order::findOrFail($id);

        // Business rule: cannot update if already shipped or delivered
        if (in_array($order->status, ['shipped', 'delivered'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot change status of shipped or delivered orders.'
            ], 400);
        }

        $order->status = $request->status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'data' => $order
        ]);
    }
}
