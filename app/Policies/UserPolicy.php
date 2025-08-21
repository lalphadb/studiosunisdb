<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('users.view');
    }

    public function view(User $user, User $model): bool
    {
        // Un utilisateur peut voir son propre profil
        if ($user->id === $model->id) {
            return true;
        }

        if (!$user->hasPermissionTo('users.view')) {
            return false;
        }

        // Scoping par école
        if (property_exists($user, 'ecole_id') && property_exists($model, 'ecole_id')) {
            if ($user->ecole_id && $model->ecole_id) {
                return $user->ecole_id === $model->ecole_id;
            }
        }

        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('users.create');
    }

    public function update(User $user, User $model): bool
    {
        // Un utilisateur peut modifier son propre profil (limité)
        if ($user->id === $model->id) {
            return true;
        }

        if (!$user->hasPermissionTo('users.edit')) {
            return false;
        }

        // Scoping par école
        if (property_exists($user, 'ecole_id') && property_exists($model, 'ecole_id')) {
            if ($user->ecole_id && $model->ecole_id) {
                return $user->ecole_id === $model->ecole_id;
            }
        }

        return true;
    }

    public function delete(User $user, User $model): bool
    {
        // Ne pas pouvoir supprimer son propre compte
        if ($user->id === $model->id) {
            return false;
        }

        if (!$user->hasPermissionTo('users.delete')) {
            return false;
        }

        // Ne pas pouvoir supprimer un superadmin
        if ($model->hasRole('superadmin')) {
            return false;
        }

        // Scoping par école
        if (property_exists($user, 'ecole_id') && property_exists($model, 'ecole_id')) {
            if ($user->ecole_id && $model->ecole_id) {
                return $user->ecole_id === $model->ecole_id;
            }
        }

        return true;
    }

    public function resetPassword(User $user, User $model): bool
    {
        if (!$user->hasPermissionTo('users.reset-password')) {
            return false;
        }

        // Scoping par école
        if (property_exists($user, 'ecole_id') && property_exists($model, 'ecole_id')) {
            if ($user->ecole_id && $model->ecole_id) {
                return $user->ecole_id === $model->ecole_id;
            }
        }

        return true;
    }

    public function manageRoles(User $user, User $model): bool
    {
        return $user->hasPermissionTo('users.manage-roles');
    }
}
