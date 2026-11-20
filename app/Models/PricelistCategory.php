<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PricelistCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
        'last_updated_at',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'last_updated_at' => 'datetime',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(PricelistItem::class, 'category_id')->orderBy('name');
    }

    public function importLogs(): HasMany
    {
        return $this->hasMany(PricelistImportLog::class, 'category_id')->latest('created_at');
    }
}
