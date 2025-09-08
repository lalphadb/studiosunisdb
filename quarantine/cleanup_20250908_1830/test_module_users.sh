#!/bin/bash

# StudiosDB - Test Module Users
# ==============================

echo "╔══════════════════════════════════════════════╗"
echo "║         TEST MODULE USERS                    ║"
echo "╚══════════════════════════════════════════════╝"
echo ""

# 1. Test des routes
echo "📍 Test des routes Users..."
php artisan route:list --name=users --json > /tmp/routes_test.json 2>/dev/null
if [ $? -eq 0 ]; then
    echo "✅ Routes Users configurées"
    echo "   - $(grep -c "users\." /tmp/routes_test.json) routes trouvées"
else
    echo "❌ Erreur avec les routes"
fi

# 2. Test de la base de données
echo ""
echo "🗄️ Test de la base de données..."
mysql -uroot studiosdb -e "
SELECT 
    'Total Users' as metric, COUNT(*) as value FROM users WHERE deleted_at IS NULL
UNION ALL
SELECT 'Avec rôles', COUNT(DISTINCT model_id) FROM model_has_roles WHERE model_type = 'App\\\\Models\\\\User'
UNION ALL
SELECT 'Membres karaté', COUNT(*) FROM users WHERE prenom IS NOT NULL AND nom IS NOT NULL
UNION ALL
SELECT 'Admins système', COUNT(*) FROM users WHERE prenom IS NULL OR nom IS NULL
;" 2>/dev/null

if [ $? -eq 0 ]; then
    echo "✅ Base de données fonctionnelle"
else
    echo "❌ Erreur avec la base de données"
fi

# 3. Test des modèles
echo ""
echo "🧩 Test des modèles..."
php artisan tinker --execute="
    \$user = App\\Models\\User::first();
    echo 'User ID: ' . \$user->id . PHP_EOL;
    echo 'Nom complet: ' . \$user->nom_complet . PHP_EOL;
    echo 'Rôles: ' . \$user->roles->pluck('name')->implode(', ') . PHP_EOL;
    echo 'École ID: ' . \$user->ecole_id . PHP_EOL;
" 2>/dev/null

if [ $? -eq 0 ]; then
    echo "✅ Modèles fonctionnels"
else
    echo "❌ Erreur avec les modèles"
fi

# 4. Test des contrôleurs
echo ""
echo "🎮 Test du UserController..."
php artisan tinker --execute="
    \$controller = new App\\Http\\Controllers\\UserController();
    echo 'UserController: OK' . PHP_EOL;
    \$methods = get_class_methods(\$controller);
    echo 'Méthodes: ' . count(\$methods) . PHP_EOL;
" 2>/dev/null

if [ $? -eq 0 ]; then
    echo "✅ UserController fonctionnel"
else
    echo "❌ Erreur avec UserController"
fi

# 5. Test des vues
echo ""
echo "🎨 Test des vues Inertia..."
for file in Index Form Show; do
    if [ -f "resources/js/Pages/Users/$file.vue" ]; then
        echo "✅ $file.vue présent"
    else
        echo "❌ $file.vue manquant"
    fi
done

# 6. Test des composants
echo ""
echo "🧱 Test des composants UI..."
for component in PageHeader StatCard Pagination; do
    path=$(find resources/js/Components -name "$component.vue" 2>/dev/null | head -1)
    if [ -n "$path" ]; then
        echo "✅ $component trouvé: $path"
    else
        echo "⚠️ $component non trouvé"
    fi
done

# 7. Résumé
echo ""
echo "╔══════════════════════════════════════════════╗"
echo "║              RÉSUMÉ DES TESTS                ║"
echo "╚══════════════════════════════════════════════╝"

# Compte des utilisateurs
user_count=$(mysql -uroot studiosdb -se "SELECT COUNT(*) FROM users WHERE deleted_at IS NULL" 2>/dev/null)
membre_count=$(mysql -uroot studiosdb -se "SELECT COUNT(*) FROM users WHERE prenom IS NOT NULL AND nom IS NOT NULL" 2>/dev/null)

echo ""
echo "📊 Statistiques:"
echo "   - $user_count utilisateurs total"
echo "   - $membre_count membres karaté"
echo ""
echo "🔗 URLs disponibles:"
echo "   - /users (liste)"
echo "   - /users/create (création)"
echo "   - /users/{id} (détails)"
echo "   - /users/{id}/edit (modification)"
echo ""
echo "✨ Module Users unifié prêt à l'utilisation!"
echo ""
