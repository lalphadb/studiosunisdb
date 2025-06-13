<?php

namespace App\Policies;

use App\Models\Seminaire;
use App\Models\User;

class SeminairePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin', 'instructeur']);
    }

    public function view(User $user, Seminaire $seminaire): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin', 'instructeur']);
    }

    public function create(User $user): bool
    {
        // Seuls superadmin et admin peuvent créer des séminaires
        return $user->hasAnyRole(['superadmin', 'admin']);
    }

    public function update(User $user, Seminaire $seminaire): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin']);
    }

    public function delete(User $user, Seminaire $seminaire): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin']);
    }
}
