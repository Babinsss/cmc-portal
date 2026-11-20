<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PricelistItem extends Model
{
    protected $fillable = [
        'category_id',
        'item_code',
        'name',
        'unit',
        'price',
        'notes',
        'is_available',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_available' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (PricelistItem $item): void {
            $item->category()?->update(['last_updated_at' => now()]);
        });

        static::deleted(function (PricelistItem $item): void {
            $item->category()?->update(['last_updated_at' => now()]);
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PricelistCategory::class, 'category_id');
    }

    public function updatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
