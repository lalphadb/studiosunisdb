<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Membre;
use Illuminate\Auth\Access\HandlesAuthorization;

class MembrePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('superadmin') || $user->can('manage_membres');
    }

    public function view(User $user, Membre $membre)
    {
        return $user->hasRole('superadmin') || $user->can('manage_membres');
    }

    public function create(User $user)
    {
        return $user->hasRole('superadmin') || $user->can('manage_membres');
    }

    public function update(User $user, Membre $membre)
    {
        return $user->hasRole('superadmin') || $user->can('manage_membres');
    }

    public function delete(User $user, Membre $membre)
    {
        return $user->hasRole('superadmin') || $user->can('manage_membres');
    }
}
