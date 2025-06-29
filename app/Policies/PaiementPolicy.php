<?php

namespace App\Policies;

use App\Models\Paiement;
use App\Models\User;

class PaiementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'instructeur']);
    }

    public function view(User $user, Paiement $paiement): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        return $user->hasAnyRole(['admin_ecole', 'instructeur']) && 
               $paiement->ecole_id === $user->ecole_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole']);
    }

    public function update(User $user, Paiement $paiement): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        return $user->hasRole('admin_ecole') && 
               $paiement->ecole_id === $user->ecole_id;
    }

    // CORRECTION ICI - signature correcte pour delete
    public function delete(User $user, Paiement $paiement): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        return $user->hasRole('admin_ecole') && 
               $paiement->ecole_id === $user->ecole_id;
    }

    /**
     * Détermine si l'utilisateur peut effectuer des actions de masse
     */
    public function bulkUpdate(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole']);
    }
}
