<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SessionCours;
use Illuminate\Auth\Access\HandlesAuthorization;

class SessionCoursPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any sessions.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-sessions');
    }

    /**
     * Determine if the user can view the session.
     */
    public function view(User $user, SessionCours $session): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasPermissionTo('view-sessions') && 
               $user->ecole_id === $session->ecole_id;
    }

    /**
     * Determine if the user can create sessions.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-sessions');
    }

    /**
     * Determine if the user can update the session.
     */
    public function update(User $user, SessionCours $session): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasPermissionTo('update-sessions') && 
               $user->ecole_id === $session->ecole_id;
    }

    /**
     * Determine if the user can delete the session.
     */
    public function delete(User $user, SessionCours $session): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasPermissionTo('delete-sessions') && 
               $user->ecole_id === $session->ecole_id &&
               $session->coursHoraires()->count() === 0 &&
               $session->inscriptionsHistorique()->count() === 0;
    }
}
