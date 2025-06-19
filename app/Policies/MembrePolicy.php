<?php
namespace App\Policies;
use App\Models\User;
use App\Models\Membre;

class MembrePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['superadmin', 'admin', 'instructeur']);
    }

    public function view(User $user, Membre $membre): bool
    {
        if ($user->hasRole('superadmin')) return true;
        if ($user->ecole_id) return $user->ecole_id === $membre->ecole_id;
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['superadmin', 'admin', 'instructeur']);
    }

    public function update(User $user, Membre $membre): bool
    {
        if ($user->hasRole('superadmin')) return true;
        if ($user->ecole_id) return $user->ecole_id === $membre->ecole_id;
        return false;
    }

    public function delete(User $user, Membre $membre): bool
    {
        return $user->hasRole('superadmin');
    }
}
