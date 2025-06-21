@extends('layouts.admin')

@section('title', 'Gestion des Cours')
@section('page-title', 'Cours')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="relative bg-gradient-to-br from-purple-600 via-blue-600 to-indigo-600 rounded-xl p-6 text-white overflow-hidden shadow-2xl">
        <div class="relative flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-2">📚 Gestion des Cours</h1>
                <p class="text-purple-100 text-lg">Planning et organisation des cours de karaté</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.cours.create') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg font-medium transition-all">
                    ➕ Nouveau cours
                </a>
            </div>
        </div>
    </div>

    {{-- Statistiques --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card-glass p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">Total cours</p>
                    <p class="text-3xl font-bold text-white">{{ $cours->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">📚</span>
                </div>
            </div>
        </div>

        <div class="card-glass p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">Cours actifs</p>
                    <p class="text-3xl font-bold text-white">{{ $cours->where('active', true)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">✅</span>
                </div>
            </div>
        </div>

        <div class="card-glass p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">Cette semaine</p>
                    <p class="text-3xl font-bold text-white">0</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">📅</span>
                </div>
            </div>
        </div>

        <div class="card-glass p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">Inscrits total</p>
                    <p class="text-3xl font-bold text-white">0</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">👥</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Liste des cours --}}
    <div class="card-glass">
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-4 rounded-t-xl">
            <h3 class="text-xl font-bold text-white">📋 Liste des cours</h3>
        </div>
        <div class="p-6">
            @if($cours->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="text-left py-3 px-4 font-medium text-gray-300">Cours</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-300">École</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-300">Niveau</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-300">Capacité</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-300">Prix</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cours as $coursItem)
                            <tr class="border-b border-gray-800 hover:bg-gray-800 hover:bg-opacity-50">
                                <td class="py-4 px-4">
                                    <div>
                                        <p class="font-medium text-white">{{ $coursItem->nom }}</p>
                                        <p class="text-sm text-gray-400">{{ $coursItem->duree_minutes }}min</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2 py-1 rounded-full text-xs bg-blue-600 text-white">
                                        {{ $coursItem->ecole->nom ?? 'Non assigné' }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2 py-1 rounded-full text-xs 
                                        @if($coursItem->niveau == 'debutant') bg-green-600 
                                        @elseif($coursItem->niveau == 'intermediaire') bg-yellow-600 
                                        @elseif($coursItem->niveau == 'avance') bg-red-600 
                                        @else bg-purple-600 @endif text-white">
                                        {{ ucfirst($coursItem->niveau) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-gray-300">{{ $coursItem->capacite_max }} places</span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-green-400 font-medium">
                                        {{ $coursItem->prix ? $coursItem->prix . '$' : 'Gratuit' }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.cours.edit', $coursItem) }}" 
                                           class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                            ✏️ Modifier
                                        </a>
                                        <form method="POST" action="{{ route('admin.cours.destroy', $coursItem) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Supprimer ce cours ?')"
                                                    class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                                🗑️ Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $cours->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gradient-to-br from-purple-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-4xl">📚</span>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Aucun cours</h3>
                    <p class="text-gray-400 mb-6">Commencez par créer votre premier cours</p>
                    <a href="{{ route('admin.cours.create') }}" 
                       class="inline-block px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        ➕ Créer un cours
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
