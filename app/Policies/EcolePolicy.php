<?php

namespace App\Policies;

use App\Models\Ecole;
use App\Models\User;

class EcolePolicy
{
    /**
     * Voir toutes les écoles (index)
     * Admin école peut accéder à l'index mais verra seulement son école
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin']);
    }

    /**
     * Voir une école spécifique
     */
    public function view(User $user, Ecole $ecole): bool
    {
        // SuperAdmin peut tout voir
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Admin école peut voir SEULEMENT son école
        if ($user->hasRole('admin')) {
            return $user->ecole_id === $ecole->id;
        }

        return false;
    }

    /**
     * Créer une école - SEULEMENT SUPERADMIN
     */
    public function create(User $user): bool
    {
        return $user->hasRole('superadmin');
    }

    /**
     * Modifier une école
     */
    public function update(User $user, Ecole $ecole): bool
    {
        // SuperAdmin peut tout modifier
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Admin école peut modifier SEULEMENT son école
        if ($user->hasRole('admin')) {
            return $user->ecole_id === $ecole->id;
        }

        return false;
    }

    /**
     * Supprimer une école - SEULEMENT SUPERADMIN
     */
    public function delete(User $user, Ecole $ecole): bool
    {
        return $user->hasRole('superadmin');
    }
}
