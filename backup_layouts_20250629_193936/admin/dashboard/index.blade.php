<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-module-header 
                title="Tableau de Bord"
                subtitle="Vue d'ensemble de votre système StudiosDB"
                icon="📊"
                colors="blue-500,cyan-600"
                :action="false"
            />

            <!-- Métriques principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl">👤</div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Utilisateurs</h3>
                                <p class="text-2xl font-bold text-blue-600">{{ \App\Models\User::count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl">🏫</div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Écoles</h3>
                                <p class="text-2xl font-bold text-green-600">{{ \App\Models\Ecole::count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl">📚</div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Cours</h3>
                                <p class="text-2xl font-bold text-purple-600">{{ \App\Models\Cours::count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl">🎯</div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Séminaires</h3>
                                <p class="text-2xl font-bold text-pink-600">{{ \App\Models\Seminaire::count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Actions Rapides</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <x-primary-button href="{{ route('admin.users.create') }}" class="justify-center py-4">
                            👤 Nouvel Utilisateur
                        </x-primary-button>
                        <x-primary-button href="{{ route('admin.ecoles.create') }}" variant="green" class="justify-center py-4">
                            🏫 Nouvelle École
                        </x-primary-button>
                        <x-primary-button href="{{ route('admin.cours.create') }}" variant="purple" class="justify-center py-4">
                            📚 Nouveau Cours
                        </x-primary-button>
                        <x-primary-button href="{{ route('admin.seminaires.create') }}" variant="indigo" class="justify-center py-4">
                            🎯 Nouveau Séminaire
                        </x-primary-button>
                    </div>
                </div>
            </div>

            <!-- Activité récente -->
            <div class="mt-8 bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Activité Récente</h3>
                    <div class="space-y-4">
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-2xl mr-4">✅</div>
                            <div>
                                <p class="font-medium">Interface standardisée</p>
                                <p class="text-sm text-gray-600">StudiosDB v4.1.10.2 - Interface complètement standardisée</p>
                            </div>
                        </div>
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-2xl mr-4">🚀</div>
                            <div>
                                <p class="font-medium">Système opérationnel</p>
                                <p class="text-sm text-gray-600">Tous les modules sont fonctionnels et sécurisés</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
