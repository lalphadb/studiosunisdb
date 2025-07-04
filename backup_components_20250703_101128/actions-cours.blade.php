@props(['cours'])

@php
$extraActions = [];

if(auth()->user()->can('viewAny-sessions')) {
    $extraActions[] = [
        'url' => route('admin.sessions.index', ['cours_id' => $cours->id]),
        'icon' => 'calendar',
        'label' => 'Sessions',
        'title' => 'Voir les sessions',
        'color' => 'indigo'
    ];
}

if(auth()->user()->can('viewAny-horaires')) {
    $extraActions[] = [
        'url' => route('admin.horaires.index', ['cours_id' => $cours->id]),
        'icon' => 'clock',
        'label' => 'Horaires',
        'title' => 'Gérer les horaires',
        'color' => 'blue'
    ];
}
@endphp

<x-module-actions 
    :item="$cours" 
    module="cours" 
    :can-view="auth()->user()->can('view', $cours)"
    :can-edit="auth()->user()->can('update', $cours)"
    :can-delete="auth()->user()->can('delete', $cours)"
    :extra-actions="$extraActions"
/>
