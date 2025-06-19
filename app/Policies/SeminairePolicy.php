<?php
namespace App\Policies;
use App\Models\User;
use App\Models\Seminaire;

class SeminairePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['superadmin', 'admin', 'instructeur', 'membre']);
    }

    public function view(User $user, Seminaire $seminaire): bool
    {
        if ($user->hasRole('superadmin')) return true;
        if ($user->ecole_id) return $user->ecole_id === $seminaire->ecole_id;
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['superadmin', 'admin']);
    }

    public function update(User $user, Seminaire $seminaire): bool
    {
        if ($user->hasRole('superadmin')) return true;
        if ($user->hasRole('admin') && $user->ecole_id === $seminaire->ecole_id) return true;
        return false;
    }

    public function delete(User $user, Seminaire $seminaire): bool
    {
        return $user->hasRole('superadmin');
    }
}
