<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Ceinture;

class CeinturePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin', 'instructeur', 'membre']) &&
               $user->can('viewAny-ceintures');
    }

    public function view(User $user, Ceinture $ceinture): bool
    {
        if (!$user->can('view-ceintures')) return false;
        
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasAnyRole(['admin_ecole', 'admin', 'instructeur', 'membre'])) {
            return $user->ecole_id === $ceinture->ecole_id;
        }
        
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin']) &&
               $user->can('create-ceintures');
    }

    public function update(User $user, Ceinture $ceinture): bool
    {
        if (!$user->can('update-ceintures')) return false;
        
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasAnyRole(['admin_ecole', 'admin'])) {
            return $user->ecole_id === $ceinture->ecole_id;
        }
        
        return false;
    }

    public function delete(User $user, Ceinture $ceinture): bool
    {
        if (!$user->can('delete-ceintures')) return false;
        
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasRole('admin_ecole')) {
            return $user->ecole_id === $ceinture->ecole_id;
        }
        
        return false;
    }

    public function attribuer(User $user, Ceinture $ceinture): bool
    {
        return $this->update($user, $ceinture);
    }

    public function createMasse(User $user): bool
    {
        return $this->create($user);
    }
}
