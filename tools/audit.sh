# === 1. STRUCTURE PROJET & DÉPENDANCES ===
echo "=== STRUCTURE PROJET ==="
pwd
ls -la
test -f composer.json && echo "✓ composer.json présent" || echo "✗ composer.json ABSENT"
test -f package.json && echo "✓ package.json présent" || echo "✗ package.json ABSENT"
test -f .env && echo "✓ .env présent" || echo "✗ .env ABSENT"

echo -e "\n=== VERSIONS ==="
php -v | head -1
test -f composer.json && grep '"laravel/framework"' composer.json
node -v && npm -v

echo -e "\n=== STACK VERIFICATION ==="
grep -q '"laravel/framework": "^12' composer.json 2>/dev/null && echo "✓ Laravel 12.x" || echo "✗ Version Laravel à vérifier"
grep -q '"inertiajs/inertia-laravel"' composer.json 2>/dev/null && echo "✓ Inertia présent" || echo "✗ Inertia ABSENT"
grep -q '"spatie/laravel-permission"' composer.json 2>/dev/null && echo "✓ Spatie Permission présent" || echo "✗ Spatie ABSENT"
grep -q '"@inertiajs/vue3"' package.json 2>/dev/null && echo "✓ Inertia Vue3 présent" || echo "✗ Inertia Vue3 ABSENT"
grep -q 'tailwindcss' package.json 2>/dev/null && echo "✓ Tailwind présent" || echo "✗ Tailwind ABSENT"

echo -e "\n=== INTERDITS ==="
grep -Rni '"stancl/tenancy"' composer.json vendor 2>/dev/null || echo "✓ OK: pas de tenancy"
grep -Rni 'livewire' composer.json resources 2>/dev/null || echo "✓ OK: pas de Livewire"

# === 2. BASE DE DONNÉES & MIGRATIONS ===
echo -e "\n=== DATABASE & MIGRATIONS ==="
php artisan migrate:status 2>/dev/null | head -20
echo "Total migrations: $(ls -1 database/migrations/*.php 2>/dev/null | wc -l)"

echo -e "\n=== TABLES CRITIQUES ==="
php artisan tinker --execute="
echo 'users.ecole_id: ' . (\Schema::hasColumn('users','ecole_id')?'✓':'✗ ABSENT');
echo PHP_EOL . 'membres.ecole_id: ' . (\Schema::hasColumn('membres','ecole_id')?'✓':'✗ ABSENT');
echo PHP_EOL . 'cours table: ' . (\Schema::hasTable('cours')?'✓':'✗ ABSENT');
echo PHP_EOL . 'membres table: ' . (\Schema::hasTable('membres')?'✓':'✗ ABSENT');
" 2>/dev/null

# === 3. ROUTES & CONTROLLERS ===
echo -e "\n=== ROUTES PRINCIPALES ==="
php artisan route:list --columns=method,uri,name,action 2>/dev/null | grep -E "(dashboard|cours|membre|user|register)" | head -20

echo -e "\n=== CONTROLLERS ==="
ls -la app/Http/Controllers/ 2>/dev/null | grep -E "(Dashboard|Cours|Membre|User)" || echo "Controllers à vérifier"

# === 4. POLICIES & PERMISSIONS ===
echo -e "\n=== POLICIES ==="
ls -la app/Policies/ 2>/dev/null || echo "✗ Dossier Policies ABSENT"
test -f app/Policies/MembrePolicy.php && echo "✓ MembrePolicy présente" || echo "✗ MembrePolicy ABSENTE"
test -f app/Policies/CoursPolicy.php && echo "✓ CoursPolicy présente" || echo "✗ CoursPolicy ABSENTE"

echo -e "\n=== TRAITS ==="
find app -name "*.php" -exec grep -l "BelongsToEcole" {} \; 2>/dev/null | head -5 || echo "✗ Trait BelongsToEcole non trouvé"

# === 5. RESOURCES INERTIA/VUE ===
echo -e "\n=== PAGES INERTIA ==="
test -d resources/js/Pages && echo "✓ Dossier Pages présent" || echo "✗ Dossier Pages ABSENT"
ls -la resources/js/Pages/ 2>/dev/null | head -10

echo -e "\n=== MODULES UI ==="
test -d resources/js/Pages/Dashboard && echo "✓ Dashboard présent" || echo "✗ Dashboard ABSENT"
test -d resources/js/Pages/Cours && echo "✓ Cours présent" || echo "✗ Cours ABSENT"
test -d resources/js/Pages/Membres && echo "✓ Membres présent" || echo "✗ Membres ABSENT"
test -d resources/js/Pages/Users && echo "✓ Users présent" || echo "✗ Users ABSENT"

echo -e "\n=== COMPOSANTS UI ==="
test -d resources/js/Components && ls resources/js/Components/*.vue 2>/dev/null | head -10 || echo "✗ Composants à vérifier"

# === 6. CONFIGURATION ==="
echo -e "\n=== CONFIG FILES ==="
test -f config/permission.php && echo "✓ config/permission.php présent" || echo "✗ config Spatie ABSENT"
test -f config/inertia.php && echo "✓ config/inertia.php présent" || echo "✗ config Inertia ABSENT"

echo -e "\n=== VITE CONFIG ==="
test -f vite.config.js && grep -n "@" vite.config.js | head -5 || echo "✗ vite.config.js à vérifier"

echo -e "\n=== APP BLADE ==="
test -f resources/views/app.blade.php && grep -n "@vite\|@inertia" resources/views/app.blade.php || echo "✗ app.blade.php à vérifier"

# === 7. MODELS ==="
echo -e "\n=== MODELS ==="
ls -la app/Models/*.php 2>/dev/null | grep -E "(User|Membre|Cours|Ecole)" || echo "Models à vérifier"

# === 8. SEEDERS & FACTORIES ==="
echo -e "\n=== SEEDERS ==="
ls -la database/seeders/*.php 2>/dev/null | head -5 || echo "✗ Seeders à vérifier"

# === 9. TESTS ==="
echo -e "\n=== TESTS ==="
ls -la tests/Feature/*.php 2>/dev/null | head -5 || echo "✗ Tests Feature à créer"
ls -la tests/Unit/*.php 2>/dev/null | head -5 || echo "✗ Tests Unit à créer"

# === 10. PERMISSIONS & STORAGE ==="
echo -e "\n=== PERMISSIONS ==="
ls -ld storage bootstrap/cache 2>/dev/null
test -w storage && echo "✓ storage writable" || echo "✗ storage permissions à corriger"

# === 11. ARTISAN STATUS ==="
echo -e "\n=== ARTISAN STATUS ==="
php artisan about 2>/dev/null | grep -E "(Laravel|Environment|Cache|Database)" | head -10

# === 12. INSCRIPTION SELF-SERVICE ==="
echo -e "\n=== INSCRIPTION PUBLIQUE ==="
php artisan route:list 2>/dev/null | grep -i "register" | head -5
test -f app/Http/Controllers/Auth/RegisterMembreController.php && echo "✓ RegisterMembreController présent" || echo "✗ RegisterMembreController ABSENT"

# === RÉSUMÉ ==="
echo -e "\n=== RÉSUMÉ RAPIDE ==="
echo "Projet dans: $(pwd)"
echo "Laravel installé: $(test -f artisan && echo '✓' || echo '✗')"
echo "Node modules: $(test -d node_modules && echo '✓' || echo '✗ non installés')"
echo "Base configurée: $(php artisan tinker --execute="try { \DB::select('SELECT 1'); echo '✓'; } catch(\Exception \$e) { echo '✗'; }" 2>/dev/null)"
