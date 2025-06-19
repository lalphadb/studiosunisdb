<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Presence;

class PresencePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['superadmin', 'admin', 'instructeur']);
    }

    public function view(User $user, Presence $presence): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
        
        // Vérifier si c'est dans la même école
        if ($user->ecole_id && $presence->membre) {
            return $user->ecole_id === $presence->membre->ecole_id;
        }
        
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['superadmin', 'admin', 'instructeur']);
    }

    public function update(User $user, Presence $presence): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
        
        if ($user->ecole_id && $presence->membre) {
            return $user->ecole_id === $presence->membre->ecole_id;
        }
        
        return false;
    }

    public function delete(User $user, Presence $presence): bool
    {
        return $user->hasRole('superadmin');
    }
}
