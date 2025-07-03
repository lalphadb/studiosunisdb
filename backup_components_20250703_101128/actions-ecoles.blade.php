@props(['ecole'])

@php
$extraActions = [];

if(auth()->user()->can('viewAny-users')) {
    $extraActions[] = [
        'url' => route('admin.users.index', ['ecole_id' => $ecole->id]),
        'icon' => 'users',
        'label' => 'Utilisateurs',
        'title' => 'Voir les utilisateurs',
        'color' => 'blue'
    ];
}

if(auth()->user()->can('viewAny-cours')) {
    $extraActions[] = [
        'url' => route('admin.cours.index', ['ecole_id' => $ecole->id]),
        'icon' => 'academic-cap',
        'label' => 'Cours',
        'title' => 'Voir les cours',
        'color' => 'purple'
    ];
}
@endphp

<x-module-actions 
    :item="$ecole" 
    module="ecoles" 
    :can-view="auth()->user()->can('view', $ecole)"
    :can-edit="auth()->user()->can('update', $ecole)"
    :can-delete="auth()->user()->can('delete', $ecole)"
    :extra-actions="$extraActions"
/>
