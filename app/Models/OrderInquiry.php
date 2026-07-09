<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderInquiry extends Model
{
    protected $fillable = [
        'customer_name',
        'phone',
        'email',
        'fulfillment_type',
        'preferred_time',
        'order_details',
        'special_instructions',
        'delivery_address',
        'is_read',
        'notification_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'notification_sent_at' => 'datetime',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderInquiryItem::class);
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->latest();
    }
}
