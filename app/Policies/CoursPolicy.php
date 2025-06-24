<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Cours;

class CoursPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin-ecole', 'admin', 'instructeur']);
    }

    public function view(User $user, Cours $cours): bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }
        
        if ($user->ecole_id) {
            return $user->ecole_id === $cours->ecole_id;
        }
        
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin-ecole', 'admin']);
    }

    public function update(User $user, Cours $cours): bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }
        
        if ($user->hasAnyRole(['admin-ecole', 'admin']) && $user->ecole_id) {
            return $user->ecole_id === $cours->ecole_id;
        }
        
        return false;
    }

    public function delete(User $user, Cours $cours): bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }
        
        if ($user->hasRole('admin-ecole') && $user->ecole_id) {
            return $user->ecole_id === $cours->ecole_id;
        }
        
        return false;
    }
}
