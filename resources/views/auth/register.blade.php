<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="mb-4">
            <!-- Logo StudiosUnisDB -->
            <div class="w-16 h-16 mx-auto mb-4 bg-green-600 rounded-full flex items-center justify-center">
                <span class="text-2xl font-bold text-white">🥋</span>
            </div>
        </div>
        <h2 class="text-3xl font-bold text-slate-100">Créer un compte</h2>
        <p class="text-slate-400 mt-2">Rejoignez StudiosUnisDB</p>
        <p class="text-sm text-slate-500 mt-1">Système de gestion des Studios Unis du Québec</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nom complet')" class="text-slate-300" />
            <x-text-input id="name" 
                          class="block mt-1 w-full bg-slate-800 border-slate-600 text-white placeholder-slate-400 focus:border-green-500 focus:ring-green-500" 
                          type="text" 
                          name="name" 
                          :value="old('name')" 
                          required 
                          autofocus 
                          autocomplete="name"
                          placeholder="Votre nom complet" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Adresse courriel')" class="text-slate-300" />
            <x-text-input id="email" 
                          class="block mt-1 w-full bg-slate-800 border-slate-600 text-white placeholder-slate-400 focus:border-green-500 focus:ring-green-500" 
                          type="email" 
                          name="email" 
                          :value="old('email')" 
                          required 
                          autocomplete="username"
                          placeholder="votre@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- École Selection -->
        <div>
            <x-input-label for="ecole_id" :value="__('École associée')" class="text-slate-300" />
            <select id="ecole_id" 
                    name="ecole_id" 
                    class="block mt-1 w-full bg-slate-800 border-slate-600 text-white focus:border-green-500 focus:ring-green-500 rounded-md" 
                    required>
                <option value="">Sélectionnez votre école</option>
                @foreach(\App\Models\Ecole::orderBy('nom')->get() as $ecole)
                    <option value="{{ $ecole->id }}" {{ old('ecole_id') == $ecole->id ? 'selected' : '' }}>
                        {{ $ecole->nom }} - {{ $ecole->ville }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('ecole_id')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Mot de passe')" class="text-slate-300" />
            <x-text-input id="password" 
                          class="block mt-1 w-full bg-slate-800 border-slate-600 text-white placeholder-slate-400 focus:border-green-500 focus:ring-green-500"
                          type="password"
                          name="password"
                          required 
                          autocomplete="new-password"
                          placeholder="Minimum 8 caractères" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" class="text-slate-300" />
            <x-text-input id="password_confirmation" 
                          class="block mt-1 w-full bg-slate-800 border-slate-600 text-white placeholder-slate-400 focus:border-green-500 focus:ring-green-500"
                          type="password"
                          name="password_confirmation" 
                          required 
                          autocomplete="new-password"
                          placeholder="Répétez votre mot de passe" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Terms and Privacy -->
        <div class="bg-slate-800 p-4 rounded-lg border border-slate-600">
            <div class="flex items-start">
                <input type="checkbox" 
                       id="terms" 
                       name="terms" 
                       required
                       class="mt-1 rounded border-slate-600 bg-slate-700 text-green-600 shadow-sm focus:ring-green-500 focus:ring-offset-slate-900">
                <label for="terms" class="ml-3 text-sm text-slate-300">
                    J'accepte les 
                    <a href="{{ route('terms') }}" class="text-green-400 hover:text-green-300" target="_blank">
                        conditions d'utilisation
                    </a> 
                    et la 
                    <a href="{{ route('privacy') }}" class="text-green-400 hover:text-green-300" target="_blank">
                        politique de confidentialité
                    </a>
                    conforme à la Loi 25 du Québec.
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div>
            <x-primary-button class="w-full justify-center bg-green-600 hover:bg-green-700 focus:bg-green-700 active:bg-green-800">
                {{ __('Créer mon compte') }}
            </x-primary-button>
        </div>

        <!-- Login Link -->
        <div class="text-center pt-4 border-t border-slate-700">
            <p class="text-sm text-slate-400">
                Déjà un compte?
                <a href="{{ route('login') }}" class="text-green-400 hover:text-green-300 transition duration-200 font-medium">
                    Se connecter
                </a>
            </p>
        </div>

        <!-- Back to Home -->
        <div class="text-center">
            <a href="{{ url('/') }}" class="text-sm text-slate-500 hover:text-slate-400 transition duration-200">
                ← Retour à l'accueil
            </a>
        </div>
    </form>
</x-guest-layout>
