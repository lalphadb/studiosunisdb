<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view-users');
    }

    public function view(User $user, User $model): bool
    {
        if ($user->can('view-users')) {
            // Multi-tenant: admin d'école ne voit que ses utilisateurs
            if ($user->ecole_id) {
                return $model->ecole_id === $user->ecole_id;
            }
            return true;
        }
        
        // Utilisateur peut voir son propre profil
        return $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->can('create-user');
    }

    public function update(User $user, User $model): bool
    {
        if ($user->can('edit-user')) {
            // Multi-tenant
            if ($user->ecole_id) {
                return $model->ecole_id === $user->ecole_id;
            }
            return true;
        }
        
        // Utilisateur peut modifier son profil
        return $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        if (!$user->can('delete-user')) {
            return false;
        }
        
        // Protection: pas de suicide
        if ($user->id === $model->id) {
            return false;
        }
        
        // Protection: SuperAdmin ne peut être supprimé
        if ($model->hasRole('superadmin')) {
            return false;
        }
        
        // Multi-tenant
        if ($user->ecole_id) {
            return $model->ecole_id === $user->ecole_id;
        }
        
        return true;
    }
}
