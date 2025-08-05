#!/bin/bash

echo "🛠️ RECOMPILATION COMPLÈTE ASSETS"
echo "================================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 1. Nettoyer complètement
echo "1. Nettoyage complet..."
rm -rf node_modules/
rm -rf public/build/
rm -f package-lock.json
php artisan optimize:clear

# 2. Créer dossiers nécessaires
echo "2. Création dossiers..."
mkdir -p public/build
mkdir -p public/build/assets

# 3. Vérifier package.json
echo "3. Package.json..."
if [ -f "package.json" ]; then
    echo "✅ Package.json existe"
    grep -A 3 -B 3 "scripts" package.json
else
    echo "❌ Package.json manquant - Création..."
    cat > package.json << 'EOF'
{
    "name": "studiosdb-v5-pro",
    "version": "5.0.0",
    "private": true,
    "type": "module",
    "scripts": {
        "dev": "vite",
        "build": "vite build",
        "preview": "vite preview"
    },
    "devDependencies": {
        "@inertiajs/vue3": "^2.0.17",
        "@tailwindcss/forms": "^0.5.10",
        "@vitejs/plugin-vue": "^6.0.1",
        "autoprefixer": "^10.4.21",
        "laravel-vite-plugin": "^1.0.21",
        "postcss": "^8.5.6",
        "tailwindcss": "^3.4.13",
        "vite": "^6.0.5",
        "vue": "^3.5.18"
    }
}
EOF
fi

# 4. Installation forcée
echo "4. Installation NPM..."
npm install --force --no-audit

# 5. Build
echo "5. Build assets..."
npm run build

# 6. Vérification
echo "6. Vérification build..."
if [ -f "public/build/manifest.json" ]; then
    echo "✅ Build OK"
    ls -la public/build/
else
    echo "❌ Build failed - Tentative dev..."
    timeout 10 npm run dev &
    sleep 8
    kill %1 2>/dev/null
fi

# 7. Permissions
echo "7. Permissions..."
sudo chown -R www-data:www-data public/build/
chmod -R 755 public/build/

# 8. Test final
echo "8. Test final..."
curl -I http://localhost:8000/login 2>/dev/null | head -1

echo ""
echo "RECOMPILATION TERMINÉE"
echo "Rafraîchissez la page dans le navigateur !"