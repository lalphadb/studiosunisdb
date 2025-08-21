<?php

namespace App\Policies;

use App\Models\Presence;
use App\Models\User;

class PresencePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin','admin_ecole','instructeur']);
    }

    public function view(User $user, Presence $presence): bool
    {
        if ($user->hasAnyRole(['superadmin','admin_ecole','instructeur'])) return true;
        // membre: accès lecture si sa propre présence
        return $presence->membre && $presence->membre->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin','admin_ecole','instructeur']);
    }

    public function update(User $user, Presence $presence): bool
    {
        return $user->hasAnyRole(['superadmin','admin_ecole','instructeur']);
    }

    public function delete(User $user, Presence $presence): bool
    {
        return $user->hasAnyRole(['superadmin','admin_ecole']);
    }
}
