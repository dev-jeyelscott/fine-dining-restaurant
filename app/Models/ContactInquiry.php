<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ContactInquiry extends Model
{
    protected $fillable = [
        'customer_name',
        'email',
        'phone',
        'subject',
        'message',
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

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->latest();
    }
}
