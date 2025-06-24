<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin-ecole', 'admin']);
    }

    public function view(User $user, User $targetUser): bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }
        
        if ($user->hasRole('admin-ecole')) {
            return $user->ecole_id === $targetUser->ecole_id;
        }
        
        if ($user->hasRole('admin')) {
            return $user->ecole_id === $targetUser->ecole_id;
        }
        
        return $user->id === $targetUser->id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin-ecole', 'admin']);
    }

    public function update(User $user, User $targetUser): bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }
        
        if ($user->hasRole('admin-ecole')) {
            return $user->ecole_id === $targetUser->ecole_id && 
                   !$targetUser->hasAnyRole(['super-admin', 'admin-ecole']);
        }
        
        if ($user->hasRole('admin')) {
            return $user->ecole_id === $targetUser->ecole_id && 
                   $targetUser->hasRole('membre');
        }
        
        return false;
    }

    public function delete(User $user, User $targetUser): bool
    {
        if ($user->id === $targetUser->id) {
            return false; // Impossible de se supprimer soi-même
        }
        
        if ($user->hasRole('super-admin')) {
            return true;
        }
        
        if ($user->hasRole('admin-ecole')) {
            return $user->ecole_id === $targetUser->ecole_id && 
                   !$targetUser->hasAnyRole(['super-admin', 'admin-ecole']);
        }
        
        return false;
    }
}
