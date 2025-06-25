<?php

namespace App\Policies;

use App\Models\UserCeinture;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserCeinturePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view-ceintures');
    }

    public function view(User $user, UserCeinture $membreCeinture): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
        // Un admin/instructeur ne peut voir que les attributions de son école
        return $user->ecole_id === $membreCeinture->user->ecole_id;
    }

    public function create(User $user): bool
    {
        return $user->can('create-ceinture');
    }

    public function update(User $user, UserCeinture $membreCeinture): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
        return $user->can('edit-ceinture') && ($user->ecole_id === $membreCeinture->user->ecole_id);
    }

    public function delete(User $user, UserCeinture $membreCeinture): bool
    {
         if ($user->hasRole('superadmin')) {
            return true;
        }
        return $user->can('delete-ceinture') && ($user->ecole_id === $membreCeinture->user->ecole_id);
    }
}
