<?php

namespace App\Policies;

use App\Models\Presence;
use App\Models\User;

class PresencePolicy
{
    /**
     * Voir toutes les présences
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin', 'instructeur']);
    }

    /**
     * Voir une présence spécifique
     */
    public function view(User $user, Presence $presence): bool
    {
        // SuperAdmin peut tout voir
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Admin/Instructeur de l'école peut voir les présences de son école
        if ($user->hasAnyRole(['admin', 'instructeur'])) {
            return $user->ecole_id === $presence->membre->ecole_id;
        }

        return false;
    }

    /**
     * Créer/Prendre une présence
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin', 'instructeur']);
    }

    /**
     * Modifier une présence
     */
    public function update(User $user, Presence $presence): bool
    {
        // SuperAdmin peut tout modifier
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Admin/Instructeur de l'école peut modifier les présences de son école
        if ($user->hasAnyRole(['admin', 'instructeur'])) {
            return $user->ecole_id === $presence->membre->ecole_id;
        }

        return false;
    }

    /**
     * Supprimer une présence
     */
    public function delete(User $user, Presence $presence): bool
    {
        return $this->update($user, $presence);
    }
}
