#!/bin/bash

echo "🎨 Application du système d'actions uniforme..."

# Users
if [ -f "resources/views/admin/users/index.blade.php" ]; then
    sed -i 's/{{.*actions.*}}/                                        <x-actions-users :user="$user" \/>/g' resources/views/admin/users/index.blade.php
    echo "✅ Users"
fi

# Cours
if [ -f "resources/views/admin/cours/index.blade.php" ]; then
    sed -i 's/{{.*actions.*}}/                                        <x-actions-cours :cours="$cours" \/>/g' resources/views/admin/cours/index.blade.php
    echo "✅ Cours"
fi

# Sessions
if [ -f "resources/views/admin/sessions/index.blade.php" ]; then
    sed -i 's/{{.*actions.*}}/                                        <x-actions-sessions :session="$session" \/>/g' resources/views/admin/sessions/index.blade.php
    echo "✅ Sessions"
fi

# Écoles
if [ -f "resources/views/admin/ecoles/index.blade.php" ]; then
    sed -i 's/{{.*actions.*}}/                                        <x-actions-ecoles :ecole="$ecole" \/>/g' resources/views/admin/ecoles/index.blade.php
    echo "✅ Écoles"
fi

# Séminaires
if [ -f "resources/views/admin/seminaires/index.blade.php" ]; then
    sed -i 's/{{.*actions.*}}/                                        <x-actions-seminaires :seminaire="$seminaire" \/>/g' resources/views/admin/seminaires/index.blade.php
    echo "✅ Séminaires"
fi

echo "🎉 Système d'actions uniforme appliqué sur tous les modules!"
