<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function table(): BelongsTo {
        return $this->belongsTo(Table::class);
    }

    public function waiter(): BelongsTo {
        return $this->belongsTo(User::class, 'waiter_id');
    }

    public function closedBy(): BelongsTo {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function items(): HasMany {
        return $this->hasMany(OrderItem::class);
    }
}
