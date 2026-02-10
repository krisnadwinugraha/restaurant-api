<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Table;
use App\Models\Food;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderService
{
    public function __construct(protected TableService $tableService) {}

    public function getAllOrders(?string $status = null, ?string $search = null)
    {
        $query = Order::with(['table', 'waiter', 'closedBy'])
            ->latest();

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                ->orWhereHas('table', function ($tableQuery) use ($search) {
                    $tableQuery->where('table_number', 'like', "%{$search}%");
                })
                ->orWhereHas('waiter', function ($waiterQuery) use ($search) {
                    $waiterQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        return $query->paginate(15);
    }

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

    public function updateOrderItem(Order $order, OrderItem $item, array $data): OrderItem
    {
        return DB::transaction(function () use ($order, $item, $data) {
            $oldSubtotal = $item->subtotal;
            $newSubtotal = $item->price * $data['quantity'];

            $item->update([
                'quantity' => $data['quantity'],
                'subtotal' => $newSubtotal,
                'notes'    => $data['notes'] ?? $item->notes,
            ]);

            $order->increment('total_amount', $newSubtotal - $oldSubtotal);

            return $item;
        });
    }

    public function removeOrderItem(Order $order, OrderItem $item): void
    {
        DB::transaction(function () use ($order, $item) {
            $order->decrement('total_amount', $item->subtotal);
            $item->delete();
        });
    }

    public function generateReceiptPdf(Order $order)
    {
        $order->load(['items.food', 'table', 'waiter', 'closedBy']);

        $data = [
            'order' => $order,
            'date' => now()->format('d/m/Y H:i'),
        ];

        $pdf = Pdf::loadView('pdf.receipt', $data)
                ->setPaper([0, 0, 226.77, 500], 'portrait');

        return $pdf;
    }
}