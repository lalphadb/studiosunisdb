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
        return $user->hasAnyRole(['super-admin', 'admin-ecole', 'admin', 'instructeur']);
    }

    public function view(User $user, Cours $cours): bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }
        
        if ($user->hasAnyRole(['admin-ecole', 'admin', 'instructeur'])) {
            return $user->ecole_id === $cours->ecole_id;
        }
        
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin-ecole', 'admin']);
    }

    public function update(User $user, Cours $cours): bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }
        
        if ($user->hasAnyRole(['admin-ecole', 'admin'])) {
            return $user->ecole_id === $cours->ecole_id;
        }
        
        return false;
    }

    public function delete(User $user, Cours $cours): bool
    {
        // Vérifier s'il y a des inscriptions
        if ($cours->inscriptions()->exists()) {
            return false;
        }
        
        if ($user->hasRole('super-admin')) {
            return true;
        }
        
        if ($user->hasRole('admin-ecole')) {
            return $user->ecole_id === $cours->ecole_id;
        }
        
        return false;
    }

    public function inscrire(User $user, Cours $cours): bool
    {
        // Un utilisateur peut s'inscrire à un cours de son école
        return $user->ecole_id === $cours->ecole_id && $cours->active && !$cours->isComplet();
    }
}
