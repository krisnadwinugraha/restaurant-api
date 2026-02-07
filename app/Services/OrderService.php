<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Table;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(protected TableService $tableService) {}

    public function openOrder(array $data, int $waiterId): Order
    {
        return DB::transaction(function () use ($data, $waiterId) {
            $table = $this->tableService->getTableById($data['table_id']);

            if ($table->status !== 'available') {
                abort(422, "Table is already occupied.");
            }

            $order = Order::create([
                'table_id' => $table->id,
                'waiter_id' => $waiterId,
                'status' => 'open',
                'total_amount' => 0,
            ]);

            $this->tableService->updateStatus($table, 'occupied');

            return $order;
        });
    }

    public function addItemToOrder(Order $order, array $data): OrderItem
    {
        return DB::transaction(function () use ($order, $data) {
            $food = Food::findOrFail($data['food_id']);
            
            $subtotal = $food->price * $data['quantity'];

            $orderItem = $order->items()->create([
                'food_id' => $food->id,
                'quantity' => $data['quantity'],
                'price' => $food->price, 
                'subtotal' => $subtotal,
                'notes' => $data['notes'] ?? null,
            ]);

            $order->increment('total_amount', $subtotal);

            return $orderItem;
        });
    }

    public function closeOrder(Order $order, int $userId): Order
    {
        return DB::transaction(function () use ($order, $userId) {
            if ($order->status === 'closed') {
                abort(422, "Order is already closed.");
            }

            $order->update([
                'status' => 'closed',
                'closed_at' => now(),
                'closed_by' => $userId,
            ]);

            $this->tableService->updateStatus($order->table, 'available');

            return $order;
        });
    }
}