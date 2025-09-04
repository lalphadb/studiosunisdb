<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Rôles autorisés à gérer les utilisateurs (hors superadmin complet).
     */
    private array $managerRoles = ['superadmin', 'admin_ecole'];

    /**
     * Voir la liste des utilisateurs.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole($this->managerRoles);
    }

    /**
     * Voir un utilisateur spécifique.
     * - superadmin: tout
     * - admin_ecole: seulement même école ou lui-même
     * - autre: seulement lui-même
     */
    public function view(User $user, User $model): bool
    {
        if ($user->hasRole('superadmin')) return true;
        if ($user->hasRole('admin_ecole')) {
            return $user->ecole_id === $model->ecole_id || $user->id === $model->id;
        }
        return $user->id === $model->id;
    }

    /**
     * Créer un utilisateur.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole($this->managerRoles);
    }

    /**
     * Mettre à jour un utilisateur.
     * - superadmin: tout
     * - admin_ecole: même école & ne peut pas modifier un superadmin
     * - autre: seulement lui-même
     */
    public function update(User $user, User $model): bool
    {
        if ($user->hasRole('superadmin')) return true;
        if ($user->hasRole('admin_ecole')) {
            if ($model->hasRole('superadmin')) return false; // pas de modification du superadmin
            return $user->ecole_id === $model->ecole_id;
        }
        return $user->id === $model->id;
    }

    /**
     * Désactiver / supprimer (soft) un utilisateur.
     */
    public function delete(User $user, User $model): bool
    {
        if ($user->id === $model->id) return false; // pas d'auto-suppression ici
        if ($user->hasRole('superadmin')) return true;
        if ($user->hasRole('admin_ecole')) {
            if ($model->hasRole('superadmin')) return false;
            return $user->ecole_id === $model->ecole_id && !$model->hasRole('admin_ecole');
        }
        return false;
    }

    /**
     * Gestion des rôles.
     */
    public function manageRoles(User $user, User $model): bool
    {
        if ($user->hasRole('superadmin')) return true;
        if ($user->hasRole('admin_ecole')) {
            if ($model->hasRole('superadmin')) return false;
            return $user->ecole_id === $model->ecole_id;
        }
        return false;
    }

    /**
     * Reset password.
     */
    public function resetPassword(User $user, User $model): bool
    {
        return $this->update($user, $model);
    }

    public function restore(User $user, User $model): bool
    {
        return $user->hasRole('superadmin');
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasRole('superadmin');
    }
}
