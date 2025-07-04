@props(['user'])

@php
$extraActions = [];

// Action spéciale pour les utilisateurs
if(auth()->user()->can('assign-roles')) {
    $extraActions[] = [
        'url' => route('admin.users.roles', $user),
        'icon' => 'user-group',
        'label' => 'Rôles',
        'title' => 'Gérer les rôles',
        'color' => 'purple'
    ];
}

if(auth()->user()->can('view', App\Models\UserCeinture::class)) {
    $extraActions[] = [
        'url' => route('admin.users.ceintures', $user),
        'icon' => 'academic-cap',
        'label' => 'Ceintures',
        'title' => 'Voir les ceintures',
        'color' => 'orange'
    ];
}
@endphp

<x-module-actions 
    :item="$user" 
    module="users" 
    :can-view="auth()->user()->can('view', $user)"
    :can-edit="auth()->user()->can('update', $user)"
    :can-delete="auth()->user()->can('delete', $user)"
    :extra-actions="$extraActions"
/>
