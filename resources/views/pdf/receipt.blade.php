<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Courier', monospace; font-size: 12px; width: 100%; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        .total { font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>
    <div class="text-center">
        <h3>RESTO API</h3>
        <p>Table: {{ $order->table->table_number }}<br>
        Date: {{ $date }}</p>
    </div>

    <div class="divider"></div>

    <table>
        @foreach($order->items as $item)
        <tr>
            <td colspan="2">{{ $item->food->name }}</td>
        </tr>
        <tr>
            <td>{{ $item->quantity }} x {{ number_format($item->price, 2) }}</td>
            <td class="text-right">{{ number_format($item->subtotal, 2) }}</td>
        </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <table>
        <tr class="total">
            <td>GRAND TOTAL</td>
            <td class="text-right">${{ number_format($order->total_amount, 2) }}</td>
        </tr>
    </table>

    <div class="divider"></div>
    <p class="text-center">Thank you for dining with us!<br>Served by: {{ $order->waiter->name }}</p>
</body>
</html>