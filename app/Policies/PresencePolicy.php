<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Presence;

class PresencePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin', 'instructeur']) &&
               $user->can('viewAny-presences');
    }

    public function view(User $user, Presence $presence): bool
    {
        if (!$user->can('view-presences')) return false;
        
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasAnyRole(['admin_ecole', 'admin', 'instructeur'])) {
            return $user->ecole_id === $presence->ecole_id;
        }
        
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin', 'instructeur']) &&
               $user->can('create-presences');
    }

    public function update(User $user, Presence $presence): bool
    {
        if (!$user->can('update-presences')) return false;
        
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasAnyRole(['admin_ecole', 'admin', 'instructeur'])) {
            return $user->ecole_id === $presence->ecole_id;
        }
        
        return false;
    }

    public function delete(User $user, Presence $presence): bool
    {
        if (!$user->can('delete-presences')) return false;
        
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasAnyRole(['admin_ecole', 'admin'])) {
            return $user->ecole_id === $presence->ecole_id;
        }
        
        return false;
    }
}
