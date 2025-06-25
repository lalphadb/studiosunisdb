<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Seminaire;

class SeminairePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin', 'instructeur']);
    }

    public function view(User $user, Seminaire $seminaire): bool
    {
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasAnyRole(['admin_ecole', 'admin', 'instructeur'])) {
            return $user->ecole_id === $seminaire->ecole_id;
        }
        
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin']);
    }

    public function update(User $user, Seminaire $seminaire): bool
    {
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasAnyRole(['admin_ecole', 'admin'])) {
            return $user->ecole_id === $seminaire->ecole_id;
        }
        
        return false;
    }

    public function delete(User $user, Seminaire $seminaire): bool
    {
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasRole('admin_ecole')) {
            return $user->ecole_id === $seminaire->ecole_id;
        }
        
        return false;
    }
}
