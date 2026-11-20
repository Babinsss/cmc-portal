<?php

namespace App\Policies;

use App\Models\PricelistItem;
use App\Models\User;

class PricelistItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isPricelistEncoder();
    }

    public function view(User $user, PricelistItem $item): bool
    {
        return $user->canManageCategory($item->category_id);
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isPricelistEncoder();
    }

    public function update(User $user, PricelistItem $item): bool
    {
        return $user->canManageCategory($item->category_id);
    }

    public function delete(User $user, PricelistItem $item): bool
    {
        return $user->canManageCategory($item->category_id);
    }
}
