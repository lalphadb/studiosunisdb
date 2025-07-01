@extends('layouts.admin')
@section('title', 'Prise de Présence')

@section('content')
<div class="space-y-6">
    <!-- Header avec x-module-header -->
    <x-module-header 
        module="presence"
        title="Prise de Présence"
        subtitle="Enregistrer les présences du cours"
        :create-route="null"
        create-text=""
        create-permission="null"
    />

    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <div class="text-white">
            <h3 class="text-lg font-semibold mb-4">📋 Prise de Présence</h3>
            <p class="text-slate-400">Fonctionnalité de prise de présence en cours de développement.</p>
        </div>
    </div>
</div>
@endsection
