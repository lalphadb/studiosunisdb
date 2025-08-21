<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin','admin_ecole']);
    }

    public function view(User $user, User $model): bool
    {
        if ($user->hasAnyRole(['superadmin','admin_ecole'])) return true;
        return $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin','admin_ecole']);
    }

    public function update(User $user, User $model): bool
    {
        if ($user->hasAnyRole(['superadmin','admin_ecole'])) return true;
        return $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('superadmin') && $user->id !== $model->id;
    }

    public function restore(User $user, User $model): bool
    {
        return $user->hasRole('superadmin');
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasRole('superadmin');
    }
}
