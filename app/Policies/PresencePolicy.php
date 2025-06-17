<?php

namespace App\Policies;

use App\Models\Presence;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PresencePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any presences.
     */
    public function viewAny(User $user)
    {
        return $user->hasAnyPermission(['presence.view', 'presence.create', 'presence.edit', 'presence.delete']);
    }

    /**
     * Determine whether the user can view the presence.
     */
    public function view(User $user, Presence $presence)
    {
        // SuperAdmin peut tout voir
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Vérifier que la présence appartient à l'école de l'utilisateur
        return $presence->cours->ecole_id === $user->ecole_id;
    }

    /**
     * Determine whether the user can create presences.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('presence.create');
    }

    /**
     * Determine whether the user can update the presence.
     */
    public function update(User $user, Presence $presence)
    {
        if (! $user->hasPermissionTo('presence.edit')) {
            return false;
        }

        // SuperAdmin peut tout modifier
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Vérifier que la présence appartient à l'école de l'utilisateur
        return $presence->cours->ecole_id === $user->ecole_id;
    }

    /**
     * Determine whether the user can delete the presence.
     */
    public function delete(User $user, Presence $presence)
    {
        if (! $user->hasPermissionTo('presence.delete')) {
            return false;
        }

        // SuperAdmin peut tout supprimer
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Vérifier que la présence appartient à l'école de l'utilisateur
        return $presence->cours->ecole_id === $user->ecole_id;
    }

    /**
     * Determine whether the user can take attendance for a course.
     */
    public function prisePresence(User $user, $cours)
    {
        if (! $user->hasPermissionTo('presence.create')) {
            return false;
        }

        // SuperAdmin peut prendre présence partout
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Vérifier que le cours appartient à l'école de l'utilisateur
        return $cours->ecole_id === $user->ecole_id;
    }
}
