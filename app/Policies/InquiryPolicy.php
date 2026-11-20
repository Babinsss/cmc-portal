<?php

namespace App\Policies;

use App\Models\Inquiry;
use App\Models\User;

class InquiryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isPublicInfoOfficer();
    }

    public function view(User $user, Inquiry $inquiry): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Inquiry $inquiry): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, Inquiry $inquiry): bool
    {
        return $user->isSuperAdmin();
    }
}
