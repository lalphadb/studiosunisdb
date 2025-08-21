<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Presence;
use Illuminate\Auth\Access\HandlesAuthorization;

class PresencePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('presences.view');
    }

    public function view(User $user, Presence $presence): bool
    {
        // Un membre peut voir ses propres présences
        if ($user->membre && $presence->membre_id === $user->membre->id) {
            return true;
        }

        if (!$user->hasPermissionTo('presences.view')) {
            return false;
        }

        // Scoping par école
        if (property_exists($user, 'ecole_id') && $presence->membre) {
            if ($user->ecole_id && $presence->membre->ecole_id) {
                return $user->ecole_id === $presence->membre->ecole_id;
            }
        }

        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('presences.marquer');
    }

    public function update(User $user, Presence $presence): bool
    {
        if (!$user->hasPermissionTo('presences.edit')) {
            return false;
        }

        // Scoping par école
        if (property_exists($user, 'ecole_id') && $presence->membre) {
            if ($user->ecole_id && $presence->membre->ecole_id) {
                return $user->ecole_id === $presence->membre->ecole_id;
            }
        }

        return true;
    }

    public function delete(User $user, Presence $presence): bool
    {
        return $this->update($user, $presence);
    }

    public function tablette(User $user): bool
    {
        return $user->hasPermissionTo('presences.tablette');
    }

    public function export(User $user): bool
    {
        return $user->hasPermissionTo('presences.export');
    }
}
