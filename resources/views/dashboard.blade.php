<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard StudiosUnisDB') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Message de succès -->
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                ✅ Vous êtes connecté avec succès !
            </div>

            <!-- Navigation rapide -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                
                <!-- Écoles -->
                <a href="{{ route('admin.ecoles.index') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-600 rounded-lg">
                            <i class="fas fa-building text-white text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Écoles</h3>
                            <p class="text-gray-600 dark:text-gray-400">Gérer les Studios Unis</p>
                            <p class="text-2xl font-bold text-blue-600">{{ App\Models\Ecole::count() }}</p>
                        </div>
                    </div>
                </a>

                <!-- Membres -->
                <a href="{{ route('admin.membres.index') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-600 rounded-lg">
                            <i class="fas fa-users text-white text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Membres</h3>
                            <p class="text-gray-600 dark:text-gray-400">Gestion des membres</p>
                            <p class="text-2xl font-bold text-green-600">{{ App\Models\Membre::count() }}</p>
                        </div>
                    </div>
                </a>

                <!-- Cours -->
                <a href="{{ route('admin.cours.index') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-600 rounded-lg">
                            <i class="fas fa-calendar-alt text-white text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Cours</h3>
                            <p class="text-gray-600 dark:text-gray-400">Planning et horaires</p>
                            <p class="text-2xl font-bold text-purple-600">{{ App\Models\Cours::count() }}</p>
                        </div>
                    </div>
                </a>

                <!-- Utilisateurs -->
                <a href="{{ route('profile.edit') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-600 rounded-lg">
                            <i class="fas fa-user-shield text-white text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Profil</h3>
                            <p class="text-gray-600 dark:text-gray-400">Mon compte</p>
                            <p class="text-sm text-gray-500">{{ auth()->user()->name }}</p>
                        </div>
                    </div>
                </a>

            </div>

            <!-- Informations système -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informations Système</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400">Application</p>
                        <p class="text-lg font-medium text-gray-900 dark:text-gray-100">StudiosUnisDB</p>
                    </div>
                    <div>
                        <p class="text-gray-600 dark:text-gray-400">Version Laravel</p>
                        <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ app()->version() }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 dark:text-gray-400">Votre rôle</p>
                        <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ auth()->user()->roles->pluck('name')->join(', ') ?: 'Utilisateur' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
