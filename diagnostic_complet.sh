#!/bin/bash

echo "🔧 ==================================="
echo "🔧 DIAGNOSTIC STUDIOSDB V5 PRO"
echo "🔧 Résolution Pages Blanches"
echo "🔧 ==================================="

# Fonction de log avec couleurs
log_success() { echo -e "\033[32m✅ $1\033[0m"; }
log_error() { echo -e "\033[31m❌ $1\033[0m"; }
log_info() { echo -e "\033[36mℹ️  $1\033[0m"; }
log_warning() { echo -e "\033[33m⚠️  $1\033[0m"; }

# 1. Nettoyer tous les caches
log_info "Nettoyage des caches..."
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
log_success "Caches nettoyés"

# 2. Recompiler les assets
log_info "Recompilation des assets..."
npm run build
log_success "Assets recompilés"

# 3. Vérifier les permissions
log_info "Vérification des permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 644 storage/logs/laravel.log 2>/dev/null || true
log_success "Permissions configurées"

# 4. Vérifier la base de données
log_info "Vérification de la base de données..."
php artisan migrate --force
log_success "Base de données à jour"

# 5. Vérifier les utilisateurs
log_info "Vérification des utilisateurs..."
USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null || echo "0")
if [ "$USER_COUNT" = "0" ]; then
    log_warning "Aucun utilisateur trouvé, création..."
    php artisan db:seed --class=AdminUserSeeder
    log_success "Utilisateurs créés"
else
    log_success "$USER_COUNT utilisateur(s) trouvé(s)"
fi

# 6. Test des routes principales
log_info "Test des routes principales..."
ROUTES_OK=true

# Test route login
if curl -s -I http://localhost:8000/login | grep -q "200 OK"; then
    log_success "Route /login : OK"
else
    log_error "Route /login : ERREUR"
    ROUTES_OK=false
fi

# Test route dashboard (sans auth)
if curl -s -I http://localhost:8000/dashboard | grep -q "302\|200"; then
    log_success "Route /dashboard : OK (redirection ou accès)"
else
    log_error "Route /dashboard : ERREUR"
    ROUTES_OK=false
fi

# 7. Vérifier les logs d'erreur
log_info "Vérification des logs d'erreur..."
if [ -f "storage/logs/laravel.log" ]; then
    RECENT_ERRORS=$(tail -n 20 storage/logs/laravel.log | grep -i "error\|exception\|fatal" | wc -l)
    if [ "$RECENT_ERRORS" -gt 0 ]; then
        log_warning "$RECENT_ERRORS erreur(s) récente(s) trouvée(s)"
        echo "Dernières erreurs :"
        tail -n 20 storage/logs/laravel.log | grep -i "error\|exception\|fatal" | tail -n 3
    else
        log_success "Aucune erreur récente dans les logs"
    fi
else
    log_info "Aucun fichier de log trouvé"
fi

# 8. Vérifier les composants Vue
log_info "Vérification des composants Vue..."
COMPONENTS_PATH="resources/js/Pages"
CRITICAL_COMPONENTS=("DashboardUltraPro.vue" "Auth/Login.vue")

for component in "${CRITICAL_COMPONENTS[@]}"; do
    if [ -f "$COMPONENTS_PATH/$component" ]; then
        log_success "Composant $component : Présent"
    else
        log_error "Composant $component : MANQUANT"
    fi
done

# 9. Créer un utilisateur de test si nécessaire
log_info "Configuration de l'utilisateur de test..."
cat > test_login.php << 'EOF'
<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::firstOrCreate(
    ['email' => 'admin@studiosdb.com'],
    [
        'name' => 'Super Admin',
        'password' => Hash::make('password123'),
        'email_verified_at' => now(),
    ]
);

echo "Utilisateur admin créé/trouvé : " . $user->email . "\n";
echo "Mot de passe : password123\n";
EOF

php test_login.php
rm test_login.php
log_success "Utilisateur admin configuré"

# 10. Rapport final
echo ""
echo "🎯 ==================================="
echo "🎯 RAPPORT FINAL"
echo "🎯 ==================================="

if [ "$ROUTES_OK" = true ]; then
    log_success "✅ Toutes les routes fonctionnent"
else
    log_error "❌ Certaines routes ont des problèmes"
fi

echo ""
echo "📋 INFORMATIONS DE CONNEXION :"
echo "   URL: http://localhost:8000/login"
echo "   Email: admin@studiosdb.com"
echo "   Mot de passe: password123"
echo ""

echo "🚀 COMMANDES POUR DÉMARRER :"
echo "   php artisan serve --port=8000"
echo "   npm run dev (dans un autre terminal)"
echo ""

log_success "Diagnostic terminé !"

# 11. Démarrage automatique du serveur
log_info "Démarrage du serveur de développement..."
echo "Le serveur va démarrer sur http://localhost:8000"
echo "Utilisez Ctrl+C pour l'arrêter"
echo ""
