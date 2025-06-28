<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InscriptionSeminaire;

class InscriptionSeminairePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin']);
    }

    public function view(User $user, InscriptionSeminaire $inscription): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if ($user->hasRole('admin')) {
            return $user->ecole_id === $inscription->user->ecole_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin']);
    }

    public function update(User $user, InscriptionSeminaire $inscription): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if ($user->hasRole('admin')) {
            return $user->ecole_id === $inscription->user->ecole_id;
        }

        return false;
    }

    public function delete(User $user, InscriptionSeminaire $inscription): bool
    {
        return $this->update($user, $inscription);
    }
}
