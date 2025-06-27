<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Seminaire;
use Illuminate\Auth\Access\HandlesAuthorization;

class SeminairePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'instructeur', 'membre']);
    }

    public function view(User $user, Seminaire $seminaire): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if ($user->hasAnyRole(['admin_ecole', 'instructeur', 'membre'])) {
            return $seminaire->ecole_id === $user->ecole_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole']);
    }

    public function update(User $user, Seminaire $seminaire): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if ($user->hasRole('admin_ecole')) {
            return $seminaire->ecole_id === $user->ecole_id;
        }

        return false;
    }

    public function delete(User $user, Seminaire $seminaire): bool
    {
        return $this->update($user, $seminaire);
    }
}
