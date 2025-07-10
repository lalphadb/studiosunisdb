<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Ecole;

class EcolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('superadmin') &&
               $user->can('viewAny-ecoles');
    }

    public function view(User $user, Ecole $ecole): bool
    {
        return $user->hasRole('superadmin') &&
               $user->can('view-ecoles');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('superadmin') &&
               $user->can('create-ecoles');
    }

    public function update(User $user, Ecole $ecole): bool
    {
        return $user->hasRole('superadmin') &&
               $user->can('update-ecoles');
    }

    public function delete(User $user, Ecole $ecole): bool
    {
        return $user->hasRole('superadmin') &&
               $user->can('delete-ecoles');
    }
}
