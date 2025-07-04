<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ecole;

class EcolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Seuls les superadmin peuvent voir toutes les écoles
        return $user->hasRole('superadmin') || $user->hasPermissionTo('viewAny-ecoles');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ecole $ecole): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
        
        // Admin d'école ne peut voir que son école
        return $user->ecole_id === $ecole->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Seuls les superadmin peuvent créer des écoles
        return $user->hasRole('superadmin') || $user->hasPermissionTo('create-ecoles');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ecole $ecole): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
        
        // Admin d'école peut modifier son école
        return $user->ecole_id === $ecole->id && $user->hasPermissionTo('update-ecoles');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ecole $ecole): bool
    {
        // Seuls les superadmin peuvent supprimer des écoles
        return $user->hasRole('superadmin') || $user->hasPermissionTo('delete-ecoles');
    }
}
