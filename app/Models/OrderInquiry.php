<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $customer_name
 * @property string $phone
 * @property string $email
 * @property string $fulfillment_type
 * @property string $preferred_time
 * @property string|null $order_details
 * @property string|null $special_instructions
 * @property string|null $delivery_address
 * @property bool $is_read
 * @property Carbon|null $notification_sent_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
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
])]
class OrderInquiry extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'notification_sent_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<OrderInquiryItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderInquiryItem::class);
    }

    /**
     * @param  Builder<OrderInquiry>  $query
     */
    #[Scope]
    protected function unread(Builder $query): void
    {
        $query->where('is_read', false);
    }

    /**
     * @param  Builder<OrderInquiry>  $query
     */
    #[Scope]
    protected function latestFirst(Builder $query): void
    {
        $query->latest();
    }
}
