<?php
namespace App\Policies;
use App\Models\User;
use App\Models\Ceinture;

class CeinturePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['superadmin', 'admin', 'instructeur', 'membre']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['superadmin', 'admin']);
    }

    public function update(User $user, Ceinture $ceinture): bool
    {
        return $user->hasRole(['superadmin', 'admin']);
    }

    public function delete(User $user, Ceinture $ceinture): bool
    {
        return $user->hasRole('superadmin');
    }
}
