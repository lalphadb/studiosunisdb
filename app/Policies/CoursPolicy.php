<?php

namespace App\Policies;

use App\Models\Cours;
use App\Models\User;

class CoursPolicy
{
    /** Rôles privilégiés */
    private array $superRoles = ['superadmin'];

    /** Rôles pouvant consulter la liste */
    private array $viewRoles = ['superadmin','admin','admin_ecole','instructeur','membre'];

    /** Rôles pouvant gérer (create / update / delete / export) */
    private array $manageRoles = ['superadmin','admin','admin_ecole'];

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole($this->viewRoles);
    }

    public function view(User $user, Cours $cours): bool
    {
        if (!$this->viewAny($user)) return false;

        // Super rôles voient tout
        if ($user->hasAnyRole($this->superRoles)) return true;

        // Autres rôles : même école
        return $cours->ecole_id === $user->ecole_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole($this->manageRoles);
    }

    public function update(User $user, Cours $cours): bool
    {
        if (!$user->hasAnyRole($this->manageRoles)) return false;

        if ($user->hasAnyRole($this->superRoles)) return true;

        return $cours->ecole_id === $user->ecole_id;
    }

    public function delete(User $user, Cours $cours): bool
    {
        return $this->update($user, $cours); // Même logique
    }

    public function export(User $user): bool
    {
        return $user->hasAnyRole($this->manageRoles);
    }
}
