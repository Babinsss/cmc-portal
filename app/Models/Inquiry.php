<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    public const STATUS_NEW = 'new';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_RESOLVED = 'resolved';

    public const STATUSES = [
        self::STATUS_NEW => 'New',
        self::STATUS_IN_PROGRESS => 'In Progress',
        self::STATUS_RESOLVED => 'Resolved',
    ];

    protected $fillable = [
        'name',
        'contact_info',
        'subject',
        'message',
        'privacy_consented',
        'status',
        'staff_notes',
    ];

    protected function casts(): array
    {
        return [
            'privacy_consented' => 'boolean',
        ];
    }
}
