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
        return $user->can('manage-membres') || $user->can('view-membres');
    }

    public function view(User $user, Membre $membre)
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
        
        return $user->ecole_id === $membre->ecole_id;
    }

    public function create(User $user)
    {
        return $user->can('manage-membres');
    }

    public function update(User $user, Membre $membre)
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
        
        return $user->ecole_id === $membre->ecole_id && $user->can('manage-membres');
    }

    public function delete(User $user, Membre $membre)
    {
        return $user->hasRole('superadmin');
    }
}
