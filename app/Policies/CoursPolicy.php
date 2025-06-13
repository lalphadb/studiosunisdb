<?php

namespace App\Policies;

use App\Models\Cours;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermission('manage_cours') || $user->hasAnyRole(['superadmin', 'admin', 'instructeur']);
    }

    public function view(User $user, Cours $cours)
    {
        // SuperAdmin : accès total
        if ($user->hasRole('superadmin')) {
            return true;
        }
        
        // Admin/Instructeur : seulement leur école
        return $user->ecole_id === $cours->ecole_id;
    }

    public function create(User $user)
    {
        return $user->hasPermission('manage_cours') || $user->hasAnyRole(['superadmin', 'admin']);
    }

    public function update(User $user, Cours $cours)
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
        
        return $user->hasRole('admin') && $user->ecole_id === $cours->ecole_id;
    }

    public function delete(User $user, Cours $cours)
    {
        return $this->update($user, $cours);
    }
}
