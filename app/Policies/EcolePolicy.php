<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ecole;

class EcolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-ecoles');
    }

    public function view(User $user, Ecole $ecole): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->can('view-ecoles') && $user->ecole_id === $ecole->id;
    }

    public function create(User $user): bool
    {
        return $user->can('create-ecole');
    }

    public function update(User $user, Ecole $ecole): bool
    {
        if ($user->isSuperAdmin()) {
            return $user->can('edit-ecole');
        }

        return $user->can('edit-ecole') && $user->ecole_id === $ecole->id;
    }

    public function delete(User $user, Ecole $ecole): bool
    {
        return $user->isSuperAdmin() && $user->can('delete-ecole');
    }

    public function restore(User $user, Ecole $ecole): bool
    {
        return $user->isSuperAdmin() && $user->can('delete-ecole');
    }

    public function forceDelete(User $user, Ecole $ecole): bool
    {
        return $user->isSuperAdmin() && $user->can('delete-ecole');
    }
}
