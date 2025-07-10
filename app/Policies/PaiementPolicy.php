<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Paiement;

class PaiementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin']) &&
               $user->can('viewAny-paiements');
    }

    public function view(User $user, Paiement $paiement): bool
    {
        if (!$user->can('view-paiements')) return false;
        
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasAnyRole(['admin_ecole', 'admin'])) {
            return $user->ecole_id === $paiement->ecole_id;
        }
        
        // Membre peut voir ses propres paiements
        if ($user->hasRole('membre')) {
            return $user->id === $paiement->user_id;
        }
        
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin']) &&
               $user->can('create-paiements');
    }

    public function update(User $user, Paiement $paiement): bool
    {
        if (!$user->can('update-paiements')) return false;
        
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasAnyRole(['admin_ecole', 'admin'])) {
            return $user->ecole_id === $paiement->ecole_id;
        }
        
        return false;
    }

    public function delete(User $user, Paiement $paiement): bool
    {
        if (!$user->can('delete-paiements')) return false;
        
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasRole('admin_ecole')) {
            return $user->ecole_id === $paiement->ecole_id;
        }
        
        return false;
    }
}
