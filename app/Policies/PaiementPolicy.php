<?php

namespace App\Policies;

use App\Models\Paiement;
use App\Models\User;

class PaiementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin', 'instructeur']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Paiement $paiement): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        return $user->ecole_id === $paiement->ecole_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Paiement $paiement): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        return $user->hasRole('admin') && 
               $user->ecole_id === $paiement->ecole_id &&
               !in_array($paiement->statut, ['valide', 'rembourse']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Paiement $paiement): bool
    {
        return $user->hasRole('superadmin') || 
               ($user->hasRole('admin') && 
                $user->ecole_id === $paiement->ecole_id &&
                $paiement->statut === 'en_attente');
    }

    /**
     * Determine whether the user can validate payments.
     */
    public function validate(User $user, Paiement $paiement): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        return $user->hasRole('admin') && 
               $user->ecole_id === $paiement->ecole_id &&
               $paiement->statut === 'recu';
    }
}
