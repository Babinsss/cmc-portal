<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 'super_admin';

    public const ROLE_PRICELIST_ENCODER = 'pricelist_encoder';

    public const ROLE_PUBLIC_INFO_OFFICER = 'public_info_officer';

    public const ROLES = [
        self::ROLE_SUPER_ADMIN => 'Super Admin (ICT)',
        self::ROLE_PRICELIST_ENCODER => 'Pricelist Encoder',
        self::ROLE_PUBLIC_INFO_OFFICER => 'Public Info Officer',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department',
        'assigned_categories',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'assigned_categories' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) $this->is_active && array_key_exists($this->role, self::ROLES);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isPricelistEncoder(): bool
    {
        return $this->role === self::ROLE_PRICELIST_ENCODER;
    }

    public function isPublicInfoOfficer(): bool
    {
        return $this->role === self::ROLE_PUBLIC_INFO_OFFICER;
    }

    public function roleLabel(): string
    {
        return self::ROLES[$this->role] ?? ucfirst(str_replace('_', ' ', (string) $this->role));
    }

    public function canManageCategory(?int $categoryId): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if (! $this->isPricelistEncoder()) {
            return false;
        }

        $assigned = $this->assigned_categories ?? [];

        // Empty list means access to all pricelist categories.
        if ($assigned === []) {
            return true;
        }

        return $categoryId !== null && in_array($categoryId, array_map('intval', $assigned), true);
    }
}
