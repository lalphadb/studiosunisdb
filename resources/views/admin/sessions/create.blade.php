@extends('layouts.admin')

@section('title', 'Nouvelle Session')

@section('content')
<div class="space-y-6">
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
        <h1 class="text-2xl font-bold text-white mb-6">Nouvelle Session</h1>
        
        <form method="POST" action="{{ route('admin.sessions.store') }}" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Nom de la session</label>
                    <input type="text" name="nom" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Ordre</label>
                    <input type="number" name="ordre" value="1" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Date de début</label>
                    <input type="date" name="date_debut" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Date de fin</label>
                    <input type="date" name="date_fin" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Créer la session
                </button>
                <a href="{{ route('admin.sessions.index') }}" class="px-6 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
