<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use App\Http\Resources\OrderResource;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\AddOrderItemRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class OrderController extends Controller implements HasMiddleware
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum'),
            new Middleware('role:waiter,api', only: ['store', 'addItem']),
            new Middleware('role:cashier|waiter,api', only: ['close']),
        ];
    }

    public function index(Request $request)
    {
        $orders = $this->orderService->getAllOrders($request->query('status'));
        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request)
    {
        $order = $this->orderService->openOrder(
            $request->validated(), 
            $request->user()->id
        );
        return new OrderResource($order->load('table', 'waiter'));
    }

    public function show(Order $order)
    {
        return new OrderResource($order->load(['items.food', 'table', 'waiter', 'closedBy']));
    }

    public function addItem(AddOrderItemRequest $request, Order $order)
    {
        $this->orderService->addItemToOrder($order, $request->validated());
        
        return new OrderResource($order->load('items.food'));
    }

    public function close(Order $order, Request $request)
    {
        $updatedOrder = $this->orderService->closeOrder($order, $request->user()->id);
        return new OrderResource($updatedOrder);
    }
}