<?php

namespace App\Policies;

use App\Models\PricelistCategory;
use App\Models\User;

class PricelistCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isPricelistEncoder();
    }

    public function view(User $user, PricelistCategory $category): bool
    {
        return $user->isSuperAdmin() || $user->canManageCategory($category->id);
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function update(User $user, PricelistCategory $category): bool
    {
        return $user->isSuperAdmin();
    }

    public function delete(User $user, PricelistCategory $category): bool
    {
        return $user->isSuperAdmin();
    }
}
