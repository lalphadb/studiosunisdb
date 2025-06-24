<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Paiement;

class PaiementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin-ecole', 'admin']);
    }

    public function view(User $user, Paiement $paiement): bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        if ($user->hasAnyRole(['admin-ecole', 'admin']) && $user->ecole_id) {
            return $user->ecole_id === $paiement->ecole_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin-ecole', 'admin']);
    }

    public function update(User $user, Paiement $paiement): bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        if ($user->hasAnyRole(['admin-ecole', 'admin']) && $user->ecole_id) {
            return $user->ecole_id === $paiement->ecole_id;
        }

        return false;
    }

    public function delete(User $user, Paiement $paiement): bool
    {
        return $this->update($user, $paiement);
    }
}
