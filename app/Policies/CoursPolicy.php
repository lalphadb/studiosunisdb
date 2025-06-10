<?php

namespace App\Policies;

use App\Models\Cours;
use App\Models\User;

class CoursPolicy
{
    /**
     * Voir tous les cours
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin', 'instructeur']);
    }

    /**
     * Voir un cours spécifique
     */
    public function view(User $user, Cours $cours): bool
    {
        // SuperAdmin peut tout voir
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Admin/Instructeur de l'école peut voir les cours de son école
        if ($user->hasAnyRole(['admin', 'instructeur'])) {
            return $user->ecole_id === $cours->ecole_id;
        }

        return false;
    }

    /**
     * Créer un cours
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin']);
    }

    /**
     * Modifier un cours
     */
    public function update(User $user, Cours $cours): bool
    {
        // SuperAdmin peut tout modifier
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Admin école peut modifier les cours de son école
        if ($user->hasRole('admin')) {
            return $user->ecole_id === $cours->ecole_id;
        }

        return false;
    }

    /**
     * Supprimer un cours
     */
    public function delete(User $user, Cours $cours): bool
    {
        return $this->update($user, $cours);
    }
}
