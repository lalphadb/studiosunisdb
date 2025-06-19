<?php
namespace App\Policies;
use App\Models\User;
use App\Models\Paiement;

class PaiementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['superadmin', 'admin']);
    }

    public function view(User $user, Paiement $paiement): bool
    {
        if ($user->hasRole('superadmin')) return true;
        if ($user->ecole_id) return $user->ecole_id === $paiement->ecole_id;
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['superadmin', 'admin']);
    }

    public function update(User $user, Paiement $paiement): bool
    {
        if ($user->hasRole('superadmin')) return true;
        if ($user->hasRole('admin') && $user->ecole_id === $paiement->ecole_id) return true;
        return false;
    }

    public function delete(User $user, Paiement $paiement): bool
    {
        return $user->hasRole('superadmin');
    }
}
