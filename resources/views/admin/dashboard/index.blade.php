<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Statistiques -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['users'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Utilisateurs</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['cours'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Cours</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['sessions'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Sessions ce mois</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['ecoles'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Écoles</div>
                    </div>
                </div>
            </div>

            <!-- Activité récente -->
            <div class="mt-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Activité récente</h3>
                        <div class="space-y-3">
                            @forelse($recentActivity ?? [] as $activity)
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-600">{{ $activity->description }}</span>
                                    <span class="ml-auto text-gray-400">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                            @empty
                                <p class="text-gray-500">Aucune activité récente</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
