<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Membre;
use Illuminate\Auth\Access\HandlesAuthorization;

class MembrePolicy
{
    use HandlesAuthorization;

    /**
     * Scoping par école (si présent sur User/Membre).
     */
    protected function sameSchool(?User $user, Membre $membre): bool
    {
        if (! $user) { return false; }
        if (property_exists($user, 'ecole_id') && property_exists($membre, 'ecole_id')) {
            return (int) $user->ecole_id === (int) $membre->ecole_id;
        }
        return true; // fallback mono-école
    }

    public function viewAny(User $user): bool
    {
        return $user->can('membres.view');
    }

    public function view(User $user, Membre $membre): bool
    {
        if ($user->can('membres.view') && $this->sameSchool($user, $membre)) {
            return true;
        }
        // Un membre peut voir son propre profil associé
        return $user->id === ($membre->user_id ?? 0);
    }

    public function create(User $user): bool
    {
        return $user->can('membres.create');
    }

    public function update(User $user, Membre $membre): bool
    {
        return $user->can('membres.edit') && $this->sameSchool($user, $membre);
    }

    public function delete(User $user, Membre $membre): bool
    {
        return $user->can('membres.delete') && $this->sameSchool($user, $membre);
    }

    public function restore(User $user, Membre $membre): bool
    {
        return $this->delete($user, $membre);
    }

    public function forceDelete(User $user, Membre $membre): bool
    {
        // généralement interdit
        return false;
    }

    public function export(User $user): bool
    {
        return $user->can('membres.export');
    }

    public function bulk(User $user): bool
    {
        return $user->can('membres.bulk');
    }

    public function changerCeinture(User $user, Membre $membre): bool
    {
        return $user->can('membres.changer-ceinture') && $this->sameSchool($user, $membre);
    }
}
