<?php

namespace App\Policies;

use App\Models\User;

class DashboardPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin_ecole', 'instructeur']);
    }

    public function viewSuperAdmin(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    public function viewAdminEcole(User $user): bool
    {
        return $user->hasRole('admin_ecole');
    }

    public function viewInstructeur(User $user): bool
    {
        return $user->hasRole('instructeur');
    }
}
