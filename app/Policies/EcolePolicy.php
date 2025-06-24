<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ecole;

class EcolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin-ecole', 'admin']);
    }

    public function view(User $user, Ecole $ecole): bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        if ($user->hasRole('admin-ecole') && $user->ecole_id) {
            return $user->ecole_id === $ecole->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('super-admin');
    }

    public function update(User $user, Ecole $ecole): bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        if ($user->hasRole('admin-ecole') && $user->ecole_id) {
            return $user->ecole_id === $ecole->id;
        }

        return false;
    }

    public function delete(User $user, Ecole $ecole): bool
    {
        return $user->hasRole('super-admin');
    }
}
