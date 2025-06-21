<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-white">
            Changer le Mot de Passe
        </h2>
        <p class="mt-1 text-sm text-gray-400">
            Assurez-vous d'utiliser un mot de passe long et aléatoire pour sécuriser votre compte.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Mot de passe actuel')" class="text-yellow-400 font-medium" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" 
                         class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:border-yellow-500 focus:ring-yellow-500" 
                         autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-red-400" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Nouveau mot de passe')" class="text-green-400 font-medium" />
            <x-text-input id="update_password_password" name="password" type="password" 
                         class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:border-green-500 focus:ring-green-500" 
                         autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-red-400" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmer le mot de passe')" class="text-green-400 font-medium" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                         class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:border-green-500 focus:ring-green-500" 
                         autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-red-400" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-green-600 hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Sauvegarder') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-400"
                >{{ __('Sauvegardé.') }}</p>
            @endif
        </div>
    </form>
</section>
