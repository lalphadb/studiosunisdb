<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemberPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }
        
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'gestionnaire', 'instructeur']);
    }

    public function view(User $user, Member $member): bool
    {
        if ($user->hasAnyRole(['admin', 'gestionnaire'])) {
            return true;
        }
        
        if ($user->hasRole('instructeur')) {
            return $member->courses()
                ->where('instructor_id', $user->id)
                ->exists();
        }
        
        if ($user->hasRole('membre')) {
            return $member->user_id === $user->id ||
                   $member->family->members()->where('user_id', $user->id)->exists();
        }
        
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'gestionnaire']);
    }

    public function update(User $user, Member $member): bool
    {
        if ($user->hasAnyRole(['admin', 'gestionnaire'])) {
            return true;
        }
        
        if ($user->hasRole('membre')) {
            return $member->user_id === $user->id;
        }
        
        return false;
    }

    public function delete(User $user, Member $member): bool
    {
        return $user->hasRole('admin');
    }

    public function restore(User $user, Member $member): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, Member $member): bool
    {
        return $user->hasRole('super-admin');
    }

    public function changeBelt(User $user, Member $member): bool
    {
        return $user->hasAnyRole(['admin', 'instructeur']);
    }

    public function export(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'gestionnaire']);
    }
}
