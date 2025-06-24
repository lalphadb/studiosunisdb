<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Seminaire;

class SeminairePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin-ecole', 'admin']);
    }

    public function view(User $user, Seminaire $seminaire): bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        if ($user->hasAnyRole(['admin-ecole', 'admin'])) {
            if ($seminaire->ecole_id && $user->ecole_id) {
                return $user->ecole_id === $seminaire->ecole_id;
            }
            return true; // Séminaires généraux
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin-ecole', 'admin']);
    }

    public function update(User $user, Seminaire $seminaire): bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        if ($user->hasAnyRole(['admin-ecole', 'admin'])) {
            if ($seminaire->ecole_id && $user->ecole_id) {
                return $user->ecole_id === $seminaire->ecole_id;
            }
            return false; // Séminaires généraux pour super-admin seulement
        }

        return false;
    }

    public function delete(User $user, Seminaire $seminaire): bool
    {
        return $this->update($user, $seminaire);
    }
}
