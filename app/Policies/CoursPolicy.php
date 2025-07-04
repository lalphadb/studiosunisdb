<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Cours;

class CoursPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'instructeur']) || 
               $user->hasPermissionTo('viewAny-cours');
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
        return $user->hasAnyRole(['superadmin', 'admin_ecole']) || 
               $user->hasPermissionTo('create-cours');
    }

    public function update(User $user, Cours $cours): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
        
        return $user->ecole_id === $cours->ecole_id;
    }

    public function delete(User $user, Cours $cours): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
        
        return $user->ecole_id === $cours->ecole_id;
    }
}
