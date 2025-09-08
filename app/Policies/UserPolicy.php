<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can perform any action.
     */
    public function before(User $user, $ability)
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin_ecole', 'instructeur']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->ecole_id === $model->ecole_id 
            && $user->hasAnyRole(['admin_ecole', 'instructeur']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin_ecole');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Ne peut pas modifier un superadmin
        if ($model->hasRole('superadmin') && !$user->hasRole('superadmin')) {
            return false;
        }
        
        return $user->ecole_id === $model->ecole_id 
            && $user->hasRole('admin_ecole');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Ne peut pas supprimer un superadmin
        if ($model->hasRole('superadmin')) {
            return false;
        }
        
        // Ne peut pas se supprimer soi-même
        if ($user->id === $model->id) {
            return false;
        }
        
        return $user->ecole_id === $model->ecole_id 
            && $user->hasRole('admin_ecole');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->ecole_id === $model->ecole_id 
            && $user->hasRole('admin_ecole');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false; // Jamais de suppression définitive
    }
}
