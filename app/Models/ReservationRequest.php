<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $customer_name
 * @property string $phone
 * @property string $email
 * @property Carbon $preferred_date
 * @property string $preferred_time
 * @property int $guest_count
 * @property string|null $special_requests
 * @property bool $is_banquet_or_event
 * @property bool $is_read
 * @property Carbon|null $notification_sent_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'customer_name',
    'phone',
    'email',
    'preferred_date',
    'preferred_time',
    'guest_count',
    'special_requests',
    'is_banquet_or_event',
    'is_read',
    'notification_sent_at',
])]
class ReservationRequest extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
            'guest_count' => 'integer',
            'is_banquet_or_event' => 'boolean',
            'is_read' => 'boolean',
            'notification_sent_at' => 'datetime',
        ];
    }

    /**
     * @param  Builder<ReservationRequest>  $query
     */
    #[Scope]
    protected function unread(Builder $query): void
    {
        $query->where('is_read', false);
    }

    /**
     * @param  Builder<ReservationRequest>  $query
     */
    #[Scope]
    protected function latestFirst(Builder $query): void
    {
        $query->latest();
    }
}
