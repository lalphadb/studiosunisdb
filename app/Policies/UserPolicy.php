<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin']) &&
               $user->can('viewAny-users');
    }

    public function view(User $user, User $targetUser): bool
    {
        if (!$user->can('view-users')) return false;
        
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasAnyRole(['admin_ecole', 'admin'])) {
            return $user->ecole_id === $targetUser->ecole_id;
        }
        
        // Utilisateur peut voir son propre profil
        return $user->id === $targetUser->id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin']) &&
               $user->can('create-users');
    }

    public function update(User $user, User $targetUser): bool
    {
        if (!$user->can('update-users')) return false;
        
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasAnyRole(['admin_ecole', 'admin'])) {
            return $user->ecole_id === $targetUser->ecole_id;
        }
        
        // Utilisateur peut modifier son propre profil (limité)
        return $user->id === $targetUser->id;
    }

    public function delete(User $user, User $targetUser): bool
    {
        if (!$user->can('delete-users')) return false;
        
        // Empêcher l'auto-suppression
        if ($user->id === $targetUser->id) return false;
        
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasRole('admin_ecole')) {
            return $user->ecole_id === $targetUser->ecole_id &&
                   !$targetUser->hasRole('superadmin');
        }
        
        return false;
    }

    public function assignRole(User $user, User $targetUser): bool
    {
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasRole('admin_ecole')) {
            return $user->ecole_id === $targetUser->ecole_id &&
                   !$targetUser->hasRole('superadmin');
        }
        
        return false;
    }

    public function resetPassword(User $user, User $targetUser): bool
    {
        return $this->update($user, $targetUser);
    }

    public function toggleStatus(User $user, User $targetUser): bool
    {
        return $this->update($user, $targetUser);
    }
}
