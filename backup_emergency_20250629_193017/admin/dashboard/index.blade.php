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

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Métriques principales -->
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
                        <x-primary-button href="{{ route('admin.users.create') }}" class="justify-center">
                            👤 Nouvel Utilisateur
                        </x-primary-button>
                        <x-primary-button href="{{ route('admin.ecoles.create') }}" variant="green" class="justify-center">
                            🏫 Nouvelle École
                        </x-primary-button>
                        <x-primary-button href="{{ route('admin.cours.create') }}" variant="purple" class="justify-center">
                            📚 Nouveau Cours
                        </x-primary-button>
                        <x-primary-button href="{{ route('admin.seminaires.create') }}" variant="indigo" class="justify-center">
                            🎯 Nouveau Séminaire
                        </x-primary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
