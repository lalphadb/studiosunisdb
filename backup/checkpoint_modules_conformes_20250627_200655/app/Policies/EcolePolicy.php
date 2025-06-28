<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ecole;

class EcolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin']);
    }

    public function view(User $user, Ecole $ecole): bool
    {
        if ($user->hasRole('superadmin')) return true;
        if ($user->hasRole('admin_ecole')) {
            return $user->ecole_id === $ecole->id;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('superadmin');
    }

    public function update(User $user, Ecole $ecole): bool
    {
        if ($user->hasRole('superadmin')) return true;
        if ($user->hasRole('admin_ecole')) {
            return $user->ecole_id === $ecole->id;
        }
        return false;
    }

    public function delete(User $user): bool
    {
        return $user->hasRole('superadmin');
    }
}
