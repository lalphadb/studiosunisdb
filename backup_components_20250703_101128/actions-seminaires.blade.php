@props(['seminaire'])

@php
$extraActions = [];

if(auth()->user()->can('viewAny-presences')) {
    $extraActions[] = [
        'url' => route('admin.presences.seminaire', $seminaire),
        'icon' => 'check-circle',
        'label' => 'Présences',
        'title' => 'Gérer les présences',
        'color' => 'emerald'
    ];
}

if(auth()->user()->can('viewAny-paiements')) {
    $extraActions[] = [
        'url' => route('admin.paiements.index', ['seminaire_id' => $seminaire->id]),
        'icon' => 'currency-dollar',
        'label' => 'Paiements',
        'title' => 'Voir les paiements',
        'color' => 'yellow'
    ];
}
@endphp

<x-module-actions 
    :item="$seminaire" 
    module="seminaires" 
    :can-view="auth()->user()->can('view', $seminaire)"
    :can-edit="auth()->user()->can('update', $seminaire)"
    :can-delete="auth()->user()->can('delete', $seminaire)"
    :extra-actions="$extraActions"
/>
