#!/bin/bash

echo "🔍 DEBUG ET CORRECTION COMPONENTS"
echo "================================"

# Vérifier où sont les components
echo "1. Vérification structure components:"
find resources/views/components -name "*.blade.php" -ls

echo ""
echo "2. Contenu du dossier components:"
ls -la resources/views/components/

echo ""
echo "3. Recherche de primary-button dans les vues:"
grep -r "primary-button" resources/views/ | head -5

echo ""
echo "4. Vérification contenu du fichier primary-button:"
if [ -f "resources/views/components/primary-button.blade.php" ]; then
    echo "✅ Fichier existe"
    wc -l resources/views/components/primary-button.blade.php
    head -5 resources/views/components/primary-button.blade.php
else
    echo "❌ Fichier n'existe pas"
fi

# Créer TOUS les components manquants immédiatement
echo ""
echo "5. Création forcée de TOUS les components:"

# primary-button
cat > resources/views/components/primary-button.blade.php << 'EOF'
@props(['type' => 'submit', 'disabled' => false])

<button type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
EOF
echo "✅ primary-button créé"

# secondary-button
cat > resources/views/components/secondary-button.blade.php << 'EOF'
@props(['type' => 'button', 'disabled' => false])

<button type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
EOF
echo "✅ secondary-button créé"

# danger-button
cat > resources/views/components/danger-button.blade.php << 'EOF'
@props(['type' => 'submit', 'disabled' => false])

<button type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
EOF
echo "✅ danger-button créé"

# input-label
cat > resources/views/components/input-label.blade.php << 'EOF'
@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
</label>
EOF
echo "✅ input-label créé"

# input-error
cat > resources/views/components/input-error.blade.php << 'EOF'
@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
EOF
echo "✅ input-error créé"

# text-input
cat > resources/views/components/text-input.blade.php << 'EOF'
@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) }}>
EOF
echo "✅ text-input créé"

# auth-session-status
cat > resources/views/components/auth-session-status.blade.php << 'EOF'
@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600']) }}>
        {{ $status }}
    </div>
@endif
EOF
echo "✅ auth-session-status créé"

# application-logo
cat > resources/views/components/application-logo.blade.php << 'EOF'
<div {{ $attributes }}>
    <svg viewBox="0 0 50 50" class="w-full h-full">
        <circle cx="25" cy="25" r="20" fill="currentColor"/>
        <text x="25" y="30" text-anchor="middle" fill="white" font-size="14" font-weight="bold">SDB</text>
    </svg>
</div>
EOF
echo "✅ application-logo créé"

echo ""
echo "6. Vérification finale components créés:"
ls -la resources/views/components/*.blade.php

echo ""
echo "7. Test permissions:"
chmod 644 resources/views/components/*.blade.php

echo ""
echo "✅ TOUS LES COMPONENTS CRÉÉS ET VÉRIFIÉS"
