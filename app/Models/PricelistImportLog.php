<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PricelistImportLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'category_id',
        'filename',
        'rows_created',
        'rows_updated',
        'rows_skipped',
        'notes',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'rows_created' => 'integer',
            'rows_updated' => 'integer',
            'rows_skipped' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PricelistCategory::class, 'category_id');
    }
}
