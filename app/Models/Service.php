<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'code',
        'name',
        'slug',
        'schedule_label',
        'is_24_7',
        'description',
        'is_highlighted',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_24_7' => 'boolean',
            'is_highlighted' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function subspecialties(): HasMany
    {
        return $this->hasMany(ServiceSubspecialty::class)->orderBy('sort_order');
    }
}
