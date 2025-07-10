<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Cours;

class CoursPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin', 'instructeur', 'membre']) &&
               $user->can('viewAny-cours');
    }

    public function view(User $user, Cours $cours): bool
    {
        if (!$user->can('view-cours')) return false;
        
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasAnyRole(['admin_ecole', 'admin', 'instructeur', 'membre'])) {
            return $user->ecole_id === $cours->ecole_id;
        }
        
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin']) &&
               $user->can('create-cours');
    }

    public function update(User $user, Cours $cours): bool
    {
        if (!$user->can('update-cours')) return false;
        
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasAnyRole(['admin_ecole', 'admin'])) {
            return $user->ecole_id === $cours->ecole_id;
        }
        
        return false;
    }

    public function delete(User $user, Cours $cours): bool
    {
        if (!$user->can('delete-cours')) return false;
        
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasRole('admin_ecole')) {
            return $user->ecole_id === $cours->ecole_id;
        }
        
        return false;
    }

    public function duplicate(User $user, Cours $cours): bool
    {
        return $this->create($user) && $this->view($user, $cours);
    }
}
