@extends('layouts.admin')

@section('title', 'Gestion des Sessions')

@section('content')
<div class="space-y-6">
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">Gestion des Sessions</h1>
                <p class="text-slate-300">Administration des sessions de cours</p>
            </div>
            <a href="{{ route('admin.sessions.create') }}" 
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                Nouvelle Session
            </a>
        </div>
        
        <div class="text-center py-8">
            <div class="w-16 h-16 bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-slate-400 text-2xl">📅</span>
            </div>
            <h3 class="text-xl text-white mb-2">Module Sessions</h3>
            <p class="text-slate-400">Les sessions de cours apparaîtront ici.</p>
        </div>
    </div>
</div>
@endsection
