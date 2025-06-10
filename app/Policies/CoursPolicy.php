<?php

namespace App\Policies;

use App\Models\Cours;
use App\Models\User;

class CoursPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-cours');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Cours $cours): bool
    {
        if ($user->can('manage-system')) {
            return true;
        }

        return $user->ecole_id === $cours->ecole_id && $user->can('view-cours');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-cours');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Cours $cours): bool
    {
        if ($user->can('manage-system')) {
            return true;
        }

        return $user->ecole_id === $cours->ecole_id && $user->can('edit-cours');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Cours $cours): bool
    {
        if ($user->can('manage-system')) {
            return true;
        }

        return $user->ecole_id === $cours->ecole_id && $user->can('delete-cours');
    }
}
