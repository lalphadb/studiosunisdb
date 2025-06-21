<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-white">
            Informations du Profil
        </h2>
        <p class="mt-1 text-sm text-gray-400">
            Mettez à jour vos informations personnelles et votre adresse email.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nom')" class="text-blue-400 font-medium" />
            <x-text-input id="name" name="name" type="text" 
                         class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:border-blue-500 focus:ring-blue-500" 
                         :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-red-400" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-green-400 font-medium" />
            <x-text-input id="email" name="email" type="email" 
                         class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:border-green-500 focus:ring-green-500" 
                         :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2 text-red-400" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3">
                    <p class="text-sm text-yellow-400">
                        Votre adresse email n'est pas vérifiée.

                        <button form="send-verification" class="underline text-yellow-300 hover:text-yellow-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            Cliquez ici pour renvoyer l'email de vérification.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-400">
                            Un nouveau lien de vérification a été envoyé à votre adresse email.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Sauvegarder') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
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
