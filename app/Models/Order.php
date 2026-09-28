<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'dining_table_id', 'customer_id', 'user_id', 'order_type',
        'status', 'subtotal', 'discount', 'tax_percent', 'tax_amount', 'total',
        'paid_amount', 'change_amount', 'payment_method', 'note', 'completed_at',
        'cancel_reason', 'cancelled_at', 'refund_amount', 'refund_reason', 'refunded_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'refund_amount' => 'decimal:2',
        'refunded_at' => 'datetime',
    ];

    protected $appends = ['net_total'];

    // Total minus whatever was refunded — what the sale is actually worth now.
    public function getNetTotalAttribute(): float
    {
        return round((float) $this->total - (float) ($this->refund_amount ?? 0), 2);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function diningTable(): BelongsTo
    {
        return $this->belongsTo(DiningTable::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Orders that actually brought money in at some point — completed sales
    // AND refunded ones (a refund started life as a completed sale).
    // Used by reports so refunded orders are netted down rather than erased.
    public function scopeCompletedOrRefunded($query)
    {
        return $query->whereIn('status', ['completed', 'refunded']);
    }
}
