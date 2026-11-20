<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    protected $fillable = [
        'title',
        'department',
        'employment_type',
        'salary_grade',
        'vacancies_count',
        'deadline',
        'description',
        'qualifications',
        'is_open',
    ];

    protected function casts(): array
    {
        return [
            'vacancies_count' => 'integer',
            'deadline' => 'date',
            'is_open' => 'boolean',
        ];
    }
}
