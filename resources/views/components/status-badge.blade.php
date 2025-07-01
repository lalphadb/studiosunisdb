@props([
    'status' => 'active',
    'label' => null
])

@php
$statusConfig = [
    'active' => ['class' => 'studiosdb-badge-active', 'label' => 'Actif'],
    'inactive' => ['class' => 'studiosdb-badge-inactive', 'label' => 'Inactif'],
    'pending' => ['class' => 'studiosdb-badge-pending', 'label' => 'En attente'],
    'expired' => ['class' => 'studiosdb-badge-expired', 'label' => 'Expiré'],
    'validated' => ['class' => 'studiosdb-badge-validated', 'label' => 'Validé']
];

$config = $statusConfig[$status] ?? $statusConfig['active'];
$displayLabel = $label ?? $config['label'];
@endphp

<span class="studiosdb-badge {{ $config['class'] }}">
    {{ $displayLabel }}
</span>
