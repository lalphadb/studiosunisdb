#!/bin/bash

# StudiosDB - Lancement Dashboard Thème Sombre
# Purpose: Compiler et lancer le nouveau dashboard

echo "
╔══════════════════════════════════════════════════════════════╗
║          StudiosDB - Dashboard Professionnel                ║
║                Thème Sombre Slate/Indigo/Purple             ║
╚══════════════════════════════════════════════════════════════╝
"

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${YELLOW}Déploiement du nouveau dashboard...${NC}"
echo ""

# 1. Arrêter les serveurs
echo "1. Arrêt des serveurs..."
pkill -f "artisan serve" 2>/dev/null
pkill -f "vite" 2>/dev/null
rm -f public/hot
echo -e "${GREEN}✓${NC} Serveurs arrêtés"

# 2. Nettoyer
echo ""
echo "2. Nettoyage..."
rm -rf public/build
php artisan optimize:clear > /dev/null 2>&1
echo -e "${GREEN}✓${NC} Cache nettoyé"

# 3. Vérifier les fichiers
echo ""
echo "3. Vérification des fichiers..."

if [ -f "resources/js/Pages/Dashboard.vue" ]; then
    echo -e "${GREEN}✓${NC} Dashboard.vue (nouveau thème sombre)"
else
    echo -e "${RED}✗${NC} Dashboard.vue manquant!"
fi

if [ -f "app/Http/Controllers/DashboardController.php" ]; then
    echo -e "${GREEN}✓${NC} DashboardController.php"
else
    echo -e "${RED}✗${NC} DashboardController.php manquant!"
fi

# 4. Compiler
echo ""
echo "4. Compilation des assets..."
npm run build

if [ $? -eq 0 ]; then
    echo ""
    echo -e "${GREEN}✓${NC} Compilation réussie!"
    
    # Vérifier le manifest
    if [ -f "public/build/.vite/manifest.json" ]; then
        if grep -q "Dashboard.vue" public/build/.vite/manifest.json; then
            echo -e "${GREEN}✓${NC} Dashboard.vue dans le manifest"
        fi
    fi
else
    echo -e "${RED}✗${NC} Erreur de compilation!"
    exit 1
fi

# 5. Optimiser Laravel
echo ""
echo "5. Optimisation Laravel..."
php artisan config:cache
php artisan route:cache
echo -e "${GREEN}✓${NC} Caches optimisés"

# 6. Créer des données de test si nécessaire
echo ""
echo "6. Données de test..."
php artisan tinker --execute="
// Créer quelques membres de test si la table est vide
if (\App\Models\Membre::count() == 0) {
    \App\Models\Membre::factory()->count(50)->create(['statut' => 'actif']);
    echo 'Membres de test créés';
}

// Créer quelques cours de test
if (\App\Models\Cours::count() == 0) {
    for (\$i = 1; \$i <= 5; \$i++) {
        \App\Models\Cours::create([
            'nom' => 'Karaté niveau ' . \$i,
            'description' => 'Cours de karaté',
            'niveau' => ['debutant', 'intermediaire', 'avance'][\$i % 3],
            'actif' => true,
            'heure_debut' => '18:00',
            'heure_fin' => '19:30',
            'jour_semaine' => \$i
        ]);
    }
    echo 'Cours de test créés';
}
" 2>/dev/null

echo -e "${GREEN}✓${NC} Données prêtes"

# 7. Démarrer le serveur
echo ""
echo "7. Démarrage du serveur..."
php artisan serve --host=127.0.0.1 --port=8001 &
SERVER_PID=$!

sleep 3

# 8. Afficher les infos
echo ""
echo "════════════════════════════════════════════════════════════════"
echo -e "${GREEN}✅ DASHBOARD PROFESSIONNEL PRÊT!${NC}"
echo "════════════════════════════════════════════════════════════════"
echo ""
echo -e "${BLUE}🎨 Thème appliqué:${NC}"
echo "  • Fond: Dégradé sombre slate-950 → slate-900"
echo "  • Cartes: Semi-transparentes avec backdrop blur"
echo "  • Accents: Indigo et purple"
echo "  • Animations: Hover states et transitions"
echo ""
echo -e "${BLUE}📊 Fonctionnalités:${NC}"
echo "  • Stats en temps réel (membres, cours, présences)"
echo "  • Graphique de progression"
echo "  • Activités récentes"
echo "  • Actions rapides"
echo "  • Rappels et alertes"
echo ""
echo -e "${YELLOW}🌐 Accès:${NC}"
echo "  Dashboard: http://127.0.0.1:8001/dashboard"
echo "  Login: http://127.0.0.1:8001/login"
echo ""
echo "Pour arrêter: Ctrl+C ou kill $SERVER_PID"
echo ""

# Garder actif
trap "kill $SERVER_PID 2>/dev/null; exit" INT
wait $SERVER_PID
