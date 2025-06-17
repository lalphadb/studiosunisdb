<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ceinture;

class CeinturePolicy
{
    public function viewAny(User $user)
    {
        return $user->can('view-ceintures');
    }

    public function view(User $user, Ceinture $ceinture)
    {
        return $user->can('view-ceintures');
    }

    public function create(User $user)
    {
        return $user->can('manage-ceintures');
    }

    public function update(User $user, Ceinture $ceinture)
    {
        return $user->can('manage-ceintures');
    }

    public function delete(User $user, Ceinture $ceinture)
    {
        return $user->can('manage-ceintures');
    }
}
