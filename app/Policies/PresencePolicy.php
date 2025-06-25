<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Presence;

class PresencePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin', 'instructeur']);
    }

    public function view(User $user, Presence $presence): bool
    {
        if ($user->hasRole('superadmin')) return true;

        if ($user->hasAnyRole(['admin_ecole', 'admin', 'instructeur'])) {
            return $user->ecole_id === $presence->cours->ecole_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin', 'instructeur']);
    }

    public function update(User $user, Presence $presence): bool
    {
        if ($user->hasRole('superadmin')) return true;

        if ($user->hasAnyRole(['admin_ecole', 'admin', 'instructeur'])) {
            return $user->ecole_id === $presence->cours->ecole_id;
        }

        return false;
    }

    public function delete(User $user, Presence $presence): bool
    {
        if ($user->hasRole('superadmin')) return true;

        if ($user->hasAnyRole(['admin_ecole', 'admin', 'instructeur'])) {
            return $user->ecole_id === $presence->cours->ecole_id;
        }

        return false;
    }
}
