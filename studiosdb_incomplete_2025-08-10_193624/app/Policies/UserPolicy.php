<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool {
        return $user->hasRole('superadmin') || $user->hasRole('admin_ecole');
    }

    public function view(User $user, User $model): bool {
        if ($user->hasRole('superadmin')) return true;
        return $user->hasRole('admin_ecole') && $user->ecole_id === $model->ecole_id;
    }

    public function create(User $user): bool {
        return $user->hasRole('superadmin') || $user->hasRole('admin_ecole');
    }

    public function update(User $user, User $model): bool {
        if ($model->id === $user->id) return true; // édition de soi via écrans profil
        if ($user->hasRole('superadmin')) return true;
        return $user->hasRole('admin_ecole') && $user->ecole_id === $model->ecole_id && !$model->hasRole('superadmin');
    }

    public function delete(User $user, User $model): bool {
        if ($model->id === $user->id) return false; // anti auto-suppression
        if ($user->hasRole('superadmin')) return true;
        return $user->hasRole('admin_ecole') && $user->ecole_id === $model->ecole_id && !$model->hasRole('superadmin');
    }
}
