<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CoursHoraire;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursHorairePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('viewAny-horaires');
    }

    public function view(User $user, CoursHoraire $horaire): bool
    {
        return $user->hasPermissionTo('view-horaires') && 
               $user->ecole_id === $horaire->ecole_id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-horaires');
    }

    public function update(User $user, CoursHoraire $horaire): bool
    {
        return $user->hasPermissionTo('update-horaires') && 
               $user->ecole_id === $horaire->ecole_id;
    }

    public function delete(User $user, CoursHoraire $horaire): bool
    {
        return $user->hasPermissionTo('delete-horaires') && 
               $user->ecole_id === $horaire->ecole_id;
    }
}
