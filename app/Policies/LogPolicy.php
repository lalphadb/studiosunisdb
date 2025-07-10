<?php

namespace App\Policies;

use App\Models\User;

class LogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('superadmin');
    }

    public function view(User $user): bool
    {
        return $user->hasRole('superadmin');
    }

    public function clear(User $user): bool
    {
        return $user->hasRole('superadmin');
    }
}
