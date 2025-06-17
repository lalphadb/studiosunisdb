<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Cours;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('view-cours');
    }

    public function view(User $user, Cours $cours)
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        return $user->ecole_id === $cours->ecole_id && $user->can('view-cours');
    }

    public function create(User $user)
    {
        return $user->can('create-cours');
    }

    public function update(User $user, Cours $cours)
    {
        if ($user->hasRole('superadmin')) {
            return $user->can('edit-cours');
        }

        return $user->ecole_id === $cours->ecole_id && $user->can('edit-cours');
    }

    public function delete(User $user, Cours $cours)
    {
        if ($user->hasRole('superadmin')) {
            return $user->can('delete-cours');
        }

        return $user->ecole_id === $cours->ecole_id && $user->can('delete-cours');
    }
}
