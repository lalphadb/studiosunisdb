<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\Ecole;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function __construct(
        private UserRepository $repository
    ) {}
    
    public function createUser(array $data): User
    {
        return DB::transaction(function() use ($data) {
            // Validation métier
            $this->validateBusinessRules($data);
            
            // Préparation
            $userData = $this->prepareUserData($data);
            
            // Création
            $user = $this->repository->create($userData);
            
            // Rôle
            if (isset($data['role'])) {
                $user->assignRole($data['role']);
            }
            
            // Log
            $this->logUserCreation($user, $data);
            
            return $user->fresh(['roles', 'ecole']);
        });
    }
    
    private function validateBusinessRules(array $data): void
    {
        // Un non-SuperAdmin ne peut pas créer un SuperAdmin
        if (($data['role'] ?? '') === 'superadmin' && 
            !auth()->user()->hasRole('superadmin')) {
            throw new \Exception('Seul un SuperAdmin peut créer un autre SuperAdmin');
        }
    }
    
    private function prepareUserData(array $data): array
    {
        $prepared = $data;
        
        if (!empty($data['password'])) {
            $prepared['password'] = Hash::make($data['password']);
        }
        
        // Multi-tenant
        if (!auth()->user()->hasRole('superadmin')) {
            $prepared['ecole_id'] = auth()->user()->ecole_id;
        }
        
        return $prepared;
    }
    
    private function logUserCreation(User $user, array $data): void
    {
        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties(['role' => $data['role'] ?? 'membre'])
            ->log('Utilisateur créé');
    }
}
