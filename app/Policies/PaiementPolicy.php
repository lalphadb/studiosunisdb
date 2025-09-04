<?php

namespace App\Policies;

use App\Models\Paiement;
use App\Models\User;

class PaiementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin','admin_ecole','instructeur']);
    }

    public function view(User $user, Paiement $paiement): bool
    {
        if ($user->hasAnyRole(['superadmin','admin_ecole','instructeur'])) return true;
        // membre: peut voir ses propres paiements si liés à son user_id via membre
        return $paiement->membre && $paiement->membre->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin','admin_ecole']);
    }

    public function update(User $user, Paiement $paiement): bool
    {
        return $user->hasAnyRole(['superadmin','admin_ecole']);
    }

    public function delete(User $user, Paiement $paiement): bool
    {
        return $user->hasAnyRole(['superadmin','admin_ecole']);
    }

    public function refund(User $user, Paiement $paiement): bool
    {
        return $user->hasAnyRole(['superadmin','admin_ecole']);
    }
}
