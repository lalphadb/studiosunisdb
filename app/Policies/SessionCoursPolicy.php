<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\SessionCours;

class SessionCoursPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin', 'instructeur']) &&
               $user->can('viewAny-sessions');
    }

    public function view(User $user, SessionCours $session): bool
    {
        if (!$user->can('view-sessions')) return false;
        
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasAnyRole(['admin_ecole', 'admin', 'instructeur'])) {
            return $user->ecole_id === $session->ecole_id;
        }
        
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin']) &&
               $user->can('create-sessions');
    }

    public function update(User $user, SessionCours $session): bool
    {
        if (!$user->can('update-sessions')) return false;
        
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasAnyRole(['admin_ecole', 'admin'])) {
            return $user->ecole_id === $session->ecole_id;
        }
        
        return false;
    }

    public function delete(User $user, SessionCours $session): bool
    {
        if (!$user->can('delete-sessions')) return false;
        
        if ($user->hasRole('superadmin')) return true;
        
        if ($user->hasRole('admin_ecole')) {
            return $user->ecole_id === $session->ecole_id;
        }
        
        return false;
    }
}
