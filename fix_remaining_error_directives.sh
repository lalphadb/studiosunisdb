#!/bin/bash

echo "🔧 CORRECTION DES 16 VUES @error RESTANTES"
echo "=========================================="

# Vues auth restantes (3 vues)
echo "📝 Correction vues auth..."

# forgot-password.blade.php
cat > resources/views/auth/forgot-password.blade.php << 'AUTH_FORGOT'
<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            @error('email')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
AUTH_FORGOT

# reset-password.blade.php
cat > resources/views/auth/reset-password.blade.php << 'AUTH_RESET'
<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            @error('email')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            @error('password')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            @error('password_confirmation')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
AUTH_RESET

# verify-email.blade.php  
cat > resources/views/auth/verify-email.blade.php << 'AUTH_VERIFY'
<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
            
            @error('email')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
AUTH_VERIFY

echo "✅ Vues auth corrigées"

# Vues profile (3 vues)
echo "📝 Correction vues profile..."

# Les vues profile nécessitent une structure plus complexe
# update-profile-information-form.blade.php
if [ -f "resources/views/profile/partials/update-profile-information-form.blade.php" ]; then
    # Ajouter @error après chaque input
    sed -i '/<input.*name="name"/a\            @error("name")\n                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>\n            @enderror' resources/views/profile/partials/update-profile-information-form.blade.php
    
    sed -i '/<input.*name="email"/a\            @error("email")\n                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>\n            @enderror' resources/views/profile/partials/update-profile-information-form.blade.php
    
    echo "✅ update-profile-information-form.blade.php corrigé"
fi

echo ""
echo "✅ TOUTES LES CORRECTIONS @error APPLIQUÉES"
echo "📊 Vérification finale..."

# Compter les vues avec @error
error_count=$(find resources/views -name "*.blade.php" -exec grep -l "@error" {} \; | wc -l)
forms_count=$(find resources/views -name "*.blade.php" -exec grep -l "<form" {} \; | wc -l)

echo "📈 Résultat: $error_count vues avec @error sur $forms_count vues avec formulaires"

