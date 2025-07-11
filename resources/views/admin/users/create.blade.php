<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Créer un utilisateur
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom -->
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                                <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('nom')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Prénom -->
                            <div>
                                <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                                <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('prenom')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- École -->
                            <div>
                                <label for="ecole_id" class="block text-sm font-medium text-gray-700">École</label>
                                <select name="ecole_id" id="ecole_id" required
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Sélectionner une école</option>
                                    @foreach($ecoles as $ecole)
                                        <option value="{{ $ecole->id }}" {{ old('ecole_id') == $ecole->id ? 'selected' : '' }}>
                                            {{ $ecole->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ecole_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Mot de passe -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                                <input type="password" name="password" id="password" required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirmation -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                                Annuler
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Créer l'utilisateur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
