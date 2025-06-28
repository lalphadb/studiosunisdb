<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ceinture;

class CeinturePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin-ecole', 'admin', 'instructeur']);
    }

    public function view(User $user, Ceinture $ceinture): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin-ecole', 'admin', 'instructeur']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin-ecole']);
    }

    public function update(User $user, Ceinture $ceinture): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin-ecole']);
    }

    public function delete(User $user, Ceinture $ceinture): bool
    {
        return $user->hasRole('super-admin');
    }
}
