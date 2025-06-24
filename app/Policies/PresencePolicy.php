<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Presence;

class PresencePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin-ecole', 'admin', 'instructeur']);
    }

    public function view(User $user, Presence $presence): bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        if ($user->hasAnyRole(['admin-ecole', 'admin', 'instructeur']) && $user->ecole_id) {
            return $user->ecole_id === $presence->cours->ecole_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin-ecole', 'admin', 'instructeur']);
    }

    public function update(User $user, Presence $presence): bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        if ($user->hasAnyRole(['admin-ecole', 'admin', 'instructeur']) && $user->ecole_id) {
            return $user->ecole_id === $presence->cours->ecole_id;
        }

        return false;
    }

    public function delete(User $user, Presence $presence): bool
    {
        return $this->update($user, $presence);
    }
}
