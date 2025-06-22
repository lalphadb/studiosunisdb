<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Seminaire;

class SeminairePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin']);
    }

    public function view(User $user, Seminaire $seminaire): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if ($user->hasRole('admin')) {
            // Si le séminaire est lié à une école, vérifier l'école
            if ($seminaire->ecole_id) {
                return $user->ecole_id === $seminaire->ecole_id;
            }
            // Sinon, tous les admins peuvent voir les séminaires généraux
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin']);
    }

    public function update(User $user, Seminaire $seminaire): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if ($user->hasRole('admin')) {
            if ($seminaire->ecole_id) {
                return $user->ecole_id === $seminaire->ecole_id;
            }
            return false; // Seul superadmin peut modifier les séminaires généraux
        }

        return false;
    }

    public function delete(User $user, Seminaire $seminaire): bool
    {
        return $this->update($user, $seminaire);
    }
}
