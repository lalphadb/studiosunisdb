<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin']);
    }

    public function view(User $user, User $targetUser): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin']);
    }

    public function update(User $user, User $targetUser): bool
    {
        // Ne peut pas modifier un utilisateur de niveau supérieur ou égal
        return $this->canManageUser($user, $targetUser);
    }

    public function delete(User $user, User $targetUser): bool
    {
        // Ne peut pas supprimer soi-même
        if ($user->id === $targetUser->id) {
            return false;
        }
        
        // Ne peut pas supprimer un utilisateur de niveau supérieur ou égal
        return $this->canManageUser($user, $targetUser);
    }

    /**
     * Vérifie si un utilisateur peut gérer un autre utilisateur
     */
    private function canManageUser(User $manager, User $target): bool
    {
        $hierarchy = [
            'superadmin' => 5,
            'admin_ecole' => 4,
            'admin' => 3,
            'instructeur' => 2,
            'membre' => 1,
        ];

        $managerLevel = 0;
        $targetLevel = 0;

        // Récupérer le niveau le plus élevé du manager
        foreach ($manager->roles as $role) {
            $level = $hierarchy[$role->name] ?? 0;
            if ($level > $managerLevel) {
                $managerLevel = $level;
            }
        }

        // Récupérer le niveau le plus élevé du target
        foreach ($target->roles as $role) {
            $level = $hierarchy[$role->name] ?? 0;
            if ($level > $targetLevel) {
                $targetLevel = $level;
            }
        }

        return $managerLevel > $targetLevel;
    }
}
