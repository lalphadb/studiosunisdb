<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }
    
    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }
    
    public function getFiltered(array $filters = []): Builder
    {
        $query = User::query();
        
        // Multi-tenant auto
        if (!auth()->user()->hasRole('superadmin')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
        
        return $query->with(['roles', 'ecole']);
    }
}
