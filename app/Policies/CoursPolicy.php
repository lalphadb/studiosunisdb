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
        if (!$this->viewAny($user)) return false;
        
        // Superadmin voit tout
        if ($user->hasRole('superadmin')) return true;
        
        // Autres rôles : même école uniquement  
        return $cours->ecole_id === $user->ecole_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin','admin_ecole']);
    }

    public function update(User $user, Cours $cours): bool
    {
        if (!$user->hasAnyRole(['superadmin','admin_ecole'])) return false;
        
        // Superadmin peut tout modifier
        if ($user->hasRole('superadmin')) return true;
        
        // Admin école : seulement cours de son école
        return $cours->ecole_id === $user->ecole_id;
    }

    public function delete(User $user, Cours $cours): bool
    {
        return $this->update($user, $cours); // Même logique
    }
    
    public function export(User $user): bool
    {
        return $user->hasAnyRole(['superadmin','admin_ecole']);
    }
}
