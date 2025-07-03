@props(['session'])

@php
$extraActions = [];

if(auth()->user()->can('manage-sessions')) {
    $extraActions[] = [
        'url' => route('admin.sessions.toggle-actif', $session),
        'icon' => $session->actif ? 'pause' : 'play',
        'label' => $session->actif ? 'Désactiver' : 'Activer',
        'title' => $session->actif ? 'Désactiver la session' : 'Activer la session',
        'color' => $session->actif ? 'yellow' : 'green'
    ];
    
    $extraActions[] = [
        'url' => route('admin.sessions.dupliquer-horaires', $session),
        'icon' => 'duplicate',
        'label' => 'Dupliquer',
        'title' => 'Dupliquer les horaires',
        'color' => 'cyan'
    ];
}

if(auth()->user()->can('viewAny-presences')) {
    $extraActions[] = [
        'url' => route('admin.presences.index', ['session_id' => $session->id]),
        'icon' => 'check-circle',
        'label' => 'Présences',
        'title' => 'Gérer les présences',
        'color' => 'emerald'
    ];
}
@endphp

<x-module-actions 
    :item="$session" 
    module="sessions" 
    :can-view="auth()->user()->can('view', $session)"
    :can-edit="auth()->user()->can('update', $session)"
    :can-delete="auth()->user()->can('delete', $session)"
    :extra-actions="$extraActions"
/>
