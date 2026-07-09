<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $order_inquiry_id
 * @property int|null $menu_item_id
 * @property string $item_name_snapshot
 * @property string|null $display_price_snapshot
 * @property int $quantity
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'order_inquiry_id',
    'menu_item_id',
    'item_name_snapshot',
    'display_price_snapshot',
    'quantity',
    'notes',
])]
class OrderInquiryItem extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'display_price_snapshot' => 'decimal:2',
            'quantity' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<OrderInquiry, $this>
     */
    public function orderInquiry(): BelongsTo
    {
        return $this->belongsTo(OrderInquiry::class);
    }

    /**
     * @return BelongsTo<MenuItem, $this>
     */
    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }
}
