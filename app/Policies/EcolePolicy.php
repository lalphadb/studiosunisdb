<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ecole;

class EcolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['superadmin', 'admin', 'instructeur']);
    }

    public function view(User $user, Ecole $ecole): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
        
        if ($user->hasRole(['admin', 'instructeur']) && $user->ecole_id) {
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
        if ($user->hasRole('superadmin')) {
            return true;
        }
        
        if ($user->hasRole('admin') && $user->ecole_id === $ecole->id) {
            return true;
        }
        
        return false;
    }

    public function delete(User $user, Ecole $ecole): bool
    {
        return $user->hasRole('superadmin');
    }
}
