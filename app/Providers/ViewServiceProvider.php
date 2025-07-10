<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Partager les données avec les vues admin
        View::composer('layouts.admin', function ($view) {
            if (!auth()->check()) {
                return;
            }
            
            $user = auth()->user();
            $ecole = $user->ecole;
            
            // Données navigation standardisées
            $navData = [
                'user_role' => $user->roles->first()?->name ?? 'membre',
                'user' => $user,
                'ecole' => $ecole,
                'permissions' => [
                    'can_view_users' => $user->can('view_users') || $user->hasRole('superadmin'),
                    'can_view_ecoles' => $user->can('view_ecoles') || $user->hasRole('superadmin'),
                    'can_view_cours' => $user->can('view_cours') || $user->hasRole(['superadmin', 'admin_ecole']),
                    'can_view_presences' => $user->can('view_presences') || $user->hasRole(['superadmin', 'admin_ecole']),
                    'can_view_paiements' => $user->can('view_paiements') || $user->hasRole(['superadmin', 'admin_ecole']),
                    'can_view_seminaires' => $user->can('view_seminaires') || $user->hasRole(['superadmin', 'admin_ecole']),
                    'can_view_ceintures' => $user->can('view_ceintures') || $user->hasRole(['superadmin', 'admin_ecole']),
                ]
            ];
            
            $view->with([
                'user' => $user,
                'ecole' => $ecole,
                'navData' => $navData
            ]);
        });
    }
}
