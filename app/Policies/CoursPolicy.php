<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Cours;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('cours.view');
    }

    public function view(User $user, Cours $cours): bool
    {
        if (!$user->hasPermissionTo('cours.view')) {
            return false;
        }

        // Scoping par école si applicable
        if (property_exists($user, 'ecole_id') && property_exists($cours, 'ecole_id')) {
            if ($user->ecole_id && $cours->ecole_id) {
                return $user->ecole_id === $cours->ecole_id;
            }
        }

        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('cours.create');
    }

    public function update(User $user, Cours $cours): bool
    {
        if (!$user->hasPermissionTo('cours.edit')) {
            return false;
        }

        // Instructeur peut modifier ses propres cours
        if ($user->hasRole('instructeur') && $cours->instructeur_id === $user->id) {
            return true;
        }

        // Scoping par école
        if (property_exists($user, 'ecole_id') && property_exists($cours, 'ecole_id')) {
            if ($user->ecole_id && $cours->ecole_id) {
                return $user->ecole_id === $cours->ecole_id;
            }
        }

        return true;
    }

    public function delete(User $user, Cours $cours): bool
    {
        if (!$user->hasPermissionTo('cours.delete')) {
            return false;
        }

        // Scoping par école
        if (property_exists($user, 'ecole_id') && property_exists($cours, 'ecole_id')) {
            if ($user->ecole_id && $cours->ecole_id) {
                return $user->ecole_id === $cours->ecole_id;
            }
        }

        return true;
    }

    public function planning(User $user): bool
    {
        return $user->hasPermissionTo('cours.planning');
    }
}
