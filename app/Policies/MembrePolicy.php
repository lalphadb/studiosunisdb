<?php

namespace App\Policies;

use App\Models\Membre;
use App\Models\User;

class MembrePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin', 'instructeur']);
    }

    public function view(User $user, Membre $membre): bool
    {
        if ($user->hasAnyRole(['superadmin', 'admin', 'instructeur'])) {
            return true;
        }

        // membre peut voir son propre profil (lien 1:1 si existant)
        return $user->id === $membre->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin']);
    }

    public function update(User $user, Membre $membre): bool
    {
        if ($user->hasAnyRole(['superadmin', 'admin'])) {
            return true;
        }

        // un membre ne modifie pas son profil (lecture seule côté membre)
        return false;
    }

    public function delete(User $user, Membre $membre): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin']);
    }
}
