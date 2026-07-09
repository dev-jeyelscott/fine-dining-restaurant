<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ReservationRequest extends Model
{
    protected $fillable = [
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
    ];

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

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->latest();
    }
}
