<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderInquiryItem extends Model
{
    protected $fillable = [
        'order_inquiry_id',
        'menu_item_id',
        'item_name_snapshot',
        'display_price_snapshot',
        'quantity',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'display_price_snapshot' => 'decimal:2',
            'quantity' => 'integer',
        ];
    }

    public function orderInquiry(): BelongsTo
    {
        return $this->belongsTo(OrderInquiry::class);
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }
}
