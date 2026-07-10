<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $menu_category_id
 * @property string $name
 * @property string|null $slug
 * @property string|null $description
 * @property string|null $price
 * @property string|null $image_path
 * @property int $sort_order
 * @property bool $is_visible
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string|null $image_url
 * @property-read MenuCategory $menuCategory
 */
#[Fillable([
    'menu_category_id',
    'name',
    'slug',
    'description',
    'price',
    'image_path',
    'sort_order',
    'is_visible',
])]
class MenuItem extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sort_order' => 'integer',
            'is_visible' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<MenuCategory, $this>
     */
    public function menuCategory(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (blank($this->image_path)) {
            return null;
        }

        return Storage::disk('public')->url($this->image_path);
    }

    /**
     * @param  Builder<MenuItem>  $query
     */
    #[Scope]
    protected function visible(Builder $query): void
    {
        $query->where('is_visible', true);
    }

    /**
     * @param  Builder<MenuItem>  $query
     */
    #[Scope]
    protected function ordered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('name');
    }
}
