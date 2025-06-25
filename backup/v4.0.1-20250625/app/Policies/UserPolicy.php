<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin']);
    }

    public function view(User $user, User $targetUser): bool
    {
        if ($user->hasRole('superadmin')) return true;
        if ($user->hasRole('admin_ecole')) {
            return $user->ecole_id === $targetUser->ecole_id;
        }
        return $user->id === $targetUser->id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin']);
    }

    public function update(User $user, User $targetUser): bool
    {
        if ($user->hasRole('superadmin')) return true;
        if ($user->hasRole('admin_ecole')) {
            return $user->ecole_id === $targetUser->ecole_id;
        }
        return false;
    }

    public function delete(User $user, User $targetUser): bool
    {
        if ($user->id === $targetUser->id) return false;
        if ($user->hasRole('superadmin')) return true;
        if ($user->hasRole('admin_ecole')) {
            return $user->ecole_id === $targetUser->ecole_id;
        }
        return false;
    }
}
