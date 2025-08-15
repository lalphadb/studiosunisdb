<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\User;

class MemberPolicy
{
    public function viewAny(User $user): bool {
        return $user->hasAnyRole(['superadmin','admin_ecole','instructeur','membre']);
    }

    public function view(User $user, Member $member): bool {
        if ($user->hasRole('superadmin')) return true;
        if ($user->hasAnyRole(['admin_ecole','instructeur'])) {
            return $user->ecole_id === $member->ecole_id;
        }
        if ($user->hasRole('membre')) {
            return $member->user_id === $user->id;
        }
        return false;
    }

    public function create(User $user): bool {
        return $user->hasAnyRole(['superadmin','admin_ecole']);
    }

    public function update(User $user, Member $member): bool {
        if ($user->hasRole('superadmin')) return true;
        return $user->hasRole('admin_ecole') && $user->ecole_id === $member->ecole_id;
    }

    public function delete(User $user, Member $member): bool {
        if ($user->hasRole('superadmin')) return true;
        return $user->hasRole('admin_ecole') && $user->ecole_id === $member->ecole_id;
    }
}
