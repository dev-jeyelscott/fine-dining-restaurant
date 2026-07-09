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
 * @property string $email
 * @property string|null $phone
 * @property string|null $subject
 * @property string $message
 * @property bool $is_read
 * @property Carbon|null $notification_sent_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'customer_name',
    'email',
    'phone',
    'subject',
    'message',
    'is_read',
    'notification_sent_at',
])]
class ContactInquiry extends Model
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
     * @param  Builder<ContactInquiry>  $query
     */
    #[Scope]
    protected function unread(Builder $query): void
    {
        $query->where('is_read', false);
    }

    /**
     * @param  Builder<ContactInquiry>  $query
     */
    #[Scope]
    protected function latestFirst(Builder $query): void
    {
        $query->latest();
    }
}
