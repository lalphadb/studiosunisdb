<?php

namespace App\Policies;

use App\Models\Cours;
use App\Models\User;

class CoursPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin','admin_ecole','instructeur','membre']);
    }

    public function view(User $user, Cours $cours): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin','admin_ecole']);
    }

    public function update(User $user, Cours $cours): bool
    {
        return $user->hasAnyRole(['superadmin','admin_ecole']);
    }

    public function delete(User $user, Cours $cours): bool
    {
        return $user->hasAnyRole(['superadmin','admin_ecole']);
    }
}
