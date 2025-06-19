<?php
namespace App\Policies;
use App\Models\User;
use App\Models\Cours;

class CoursPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['superadmin', 'admin', 'instructeur', 'membre']);
    }

    public function view(User $user, Cours $cours): bool
    {
        if ($user->hasRole('superadmin')) return true;
        if ($user->ecole_id) return $user->ecole_id === $cours->ecole_id;
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['superadmin', 'admin', 'instructeur']);
    }

    public function update(User $user, Cours $cours): bool
    {
        if ($user->hasRole('superadmin')) return true;
        if ($user->hasRole(['admin', 'instructeur']) && $user->ecole_id === $cours->ecole_id) return true;
        return false;
    }

    public function delete(User $user, Cours $cours): bool
    {
        return $user->hasRole('superadmin');
    }
}
