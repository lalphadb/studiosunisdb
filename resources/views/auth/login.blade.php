<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="mb-4">
            <!-- Logo StudiosUnisDB -->
            <div class="w-16 h-16 mx-auto mb-4 bg-blue-600 rounded-full flex items-center justify-center">
                <span class="text-2xl font-bold text-white">🥋</span>
            </div>
        </div>
        <h2 class="text-3xl font-bold text-slate-100">StudiosUnisDB</h2>
        <p class="text-slate-400 mt-2">Connexion au système de gestion</p>
        <p class="text-sm text-slate-500 mt-1">22 Studios Unis du Québec</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Adresse courriel')" class="text-slate-300" />
            <x-text-input id="email" 
                          class="block mt-1 w-full bg-slate-800 border-slate-600 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500" 
                          type="email" 
                          name="email" 
                          :value="old('email')" 
                          required 
                          autofocus 
                          autocomplete="username"
                          placeholder="votre@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Mot de passe')" class="text-slate-300" />
            <x-text-input id="password" 
                          class="block mt-1 w-full bg-slate-800 border-slate-600 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500" 
                          type="password" 
                          name="password" 
                          required 
                          autocomplete="current-password"
                          placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" 
                       type="checkbox" 
                       class="rounded border-slate-600 bg-slate-800 text-blue-600 shadow-sm focus:ring-blue-500 focus:ring-offset-slate-900" 
                       name="remember">
                <span class="ml-2 text-sm text-slate-400">{{ __('Se souvenir de moi') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-blue-400 hover:text-blue-300 transition duration-200" 
                   href="{{ route('password.request') }}">
                    {{ __('Mot de passe oublié?') }}
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div>
            <x-primary-button class="w-full justify-center bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800">
                {{ __('Se connecter') }}
            </x-primary-button>
        </div>

        <!-- Registration Link -->
        @if (Route::has('register'))
            <div class="text-center pt-4 border-t border-slate-700">
                <p class="text-sm text-slate-400">
                    Pas encore de compte?
                    <a href="{{ route('register') }}" class="text-blue-400 hover:text-blue-300 transition duration-200 font-medium">
                        Créer un compte
                    </a>
                </p>
            </div>
        @endif

        <!-- Back to Home -->
        <div class="text-center">
            <a href="{{ url('/') }}" class="text-sm text-slate-500 hover:text-slate-400 transition duration-200">
                ← Retour à l'accueil
            </a>
        </div>
    </form>
</x-guest-layout>
