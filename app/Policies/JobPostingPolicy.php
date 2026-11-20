<?php

namespace App\Policies;

use App\Models\JobPosting;
use App\Models\User;

class JobPostingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isPublicInfoOfficer();
    }

    public function view(User $user, JobPosting $jobPosting): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, JobPosting $jobPosting): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, JobPosting $jobPosting): bool
    {
        return $this->viewAny($user);
    }
}
