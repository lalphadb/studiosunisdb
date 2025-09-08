<?php

namespace App\Policies;

use App\Models\Ceinture;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CeinturePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        // Tous les utilisateurs peuvent voir les ceintures
        return true;
    }

    public function view(User $user, Ceinture $ceinture): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole']);
    }

    public function update(User $user, Ceinture $ceinture): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole']);
    }

    public function delete(User $user, Ceinture $ceinture): bool
    {
        return $user->hasRole('superadmin');
    }
}
