<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserCeinture;

class UserCeinturePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'instructeur']);
    }

    public function view(User $user, UserCeinture $userCeinture): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if ($user->hasAnyRole(['admin_ecole', 'instructeur'])) {
            return $user->ecole_id === $userCeinture->ecole_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'instructeur']);
    }

    public function update(User $user, UserCeinture $userCeinture): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if ($user->hasAnyRole(['admin_ecole', 'instructeur'])) {
            return $user->ecole_id === $userCeinture->ecole_id;
        }

        return false;
    }

    public function delete(User $user, UserCeinture $userCeinture): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if ($user->hasRole('admin_ecole')) {
            return $user->ecole_id === $userCeinture->ecole_id;
        }

        return false;
    }
}
