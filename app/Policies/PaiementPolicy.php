<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Paiement;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaiementPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('paiements.view');
    }

    public function view(User $user, Paiement $paiement): bool
    {
        // Un membre peut voir ses propres paiements
        if ($user->membre && $paiement->membre_id === $user->membre->id) {
            return true;
        }

        if (!$user->hasPermissionTo('paiements.view')) {
            return false;
        }

        // Scoping par école
        if (property_exists($user, 'ecole_id') && $paiement->membre) {
            if ($user->ecole_id && $paiement->membre->ecole_id) {
                return $user->ecole_id === $paiement->membre->ecole_id;
            }
        }

        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('paiements.create');
    }

    public function update(User $user, Paiement $paiement): bool
    {
        if (!$user->hasPermissionTo('paiements.edit')) {
            return false;
        }

        // Scoping par école
        if (property_exists($user, 'ecole_id') && $paiement->membre) {
            if ($user->ecole_id && $paiement->membre->ecole_id) {
                return $user->ecole_id === $paiement->membre->ecole_id;
            }
        }

        return true;
    }

    public function delete(User $user, Paiement $paiement): bool
    {
        if (!$user->hasPermissionTo('paiements.delete')) {
            return false;
        }

        // Scoping par école
        if (property_exists($user, 'ecole_id') && $paiement->membre) {
            if ($user->ecole_id && $paiement->membre->ecole_id) {
                return $user->ecole_id === $paiement->membre->ecole_id;
            }
        }

        return true;
    }

    public function confirmer(User $user, Paiement $paiement): bool
    {
        if (!$user->hasPermissionTo('paiements.confirmer')) {
            return false;
        }

        // Scoping par école
        if (property_exists($user, 'ecole_id') && $paiement->membre) {
            if ($user->ecole_id && $paiement->membre->ecole_id) {
                return $user->ecole_id === $paiement->membre->ecole_id;
            }
        }

        return true;
    }

    public function rembourser(User $user, Paiement $paiement): bool
    {
        if (!$user->hasPermissionTo('paiements.rembourser')) {
            return false;
        }

        // Scoping par école
        if (property_exists($user, 'ecole_id') && $paiement->membre) {
            if ($user->ecole_id && $paiement->membre->ecole_id) {
                return $user->ecole_id === $paiement->membre->ecole_id;
            }
        }

        return true;
    }
}
