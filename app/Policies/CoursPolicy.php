<?php

namespace App\Policies;

use App\Models\Cours;
use App\Models\User;

class CoursPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin', 'instructeur']);
    }

    public function view(User $user, Cours $cours): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        return $user->ecole_id === $cours->ecole_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin']);
    }

    public function update(User $user, Cours $cours): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        return $user->hasRole('admin') && $user->ecole_id === $cours->ecole_id;
    }

    public function delete(User $user, Cours $cours): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        return $user->hasRole('admin') && $user->ecole_id === $cours->ecole_id;
    }
}
