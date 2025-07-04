<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole']) || 
               $user->hasPermissionTo('viewAny-users');
    }

    public function view(User $user, User $targetUser): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
        
        // Utilisateur peut voir son propre profil
        if ($user->id === $targetUser->id) {
            return true;
        }
        
        // Admin d'école peut voir les utilisateurs de son école
        return $user->hasRole('admin_ecole') && $user->ecole_id === $targetUser->ecole_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole']) || 
               $user->hasPermissionTo('create-users');
    }

    public function update(User $user, User $targetUser): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
        
        // Admin d'école peut modifier les utilisateurs de son école
        return $user->hasRole('admin_ecole') && $user->ecole_id === $targetUser->ecole_id;
    }

    public function delete(User $user, User $targetUser): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
        
        // Ne peut pas supprimer son propre compte
        if ($user->id === $targetUser->id) {
            return false;
        }
        
        // Admin d'école peut supprimer les utilisateurs de son école
        return $user->hasRole('admin_ecole') && $user->ecole_id === $targetUser->ecole_id;
    }
}
