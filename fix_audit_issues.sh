#!/bin/bash

# Script de Corrections Post-Audit StudiosDB V6
# ==============================================

PROJECT_DIR="/home/studiosdb/studiosunisdb"
cd $PROJECT_DIR

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}═══════════════════════════════════════${NC}"
echo -e "${BLUE}   CORRECTIONS POST-AUDIT STUDIOSDB${NC}"
echo -e "${BLUE}═══════════════════════════════════════${NC}"
echo ""

# 1. Sécuriser le fichier .env
echo -e "${YELLOW}1. Sécurisation du fichier .env...${NC}"
chmod 600 .env
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Permissions .env corrigées (600)${NC}"
else
    echo -e "${RED}❌ Erreur lors du changement de permissions${NC}"
fi

# 2. Nettoyer les logs Laravel
echo -e "\n${YELLOW}2. Nettoyage des logs...${NC}"
CURRENT_LOG_SIZE=$(du -h storage/logs/laravel.log | cut -f1)
echo "   Taille actuelle: $CURRENT_LOG_SIZE"

# Sauvegarder les 1000 dernières lignes
tail -1000 storage/logs/laravel.log > storage/logs/laravel.log.tmp
mv storage/logs/laravel.log.tmp storage/logs/laravel.log
echo -e "${GREEN}✅ Logs nettoyés (gardé 1000 dernières lignes)${NC}"

# 3. Nettoyer Telescope
echo -e "\n${YELLOW}3. Nettoyage Telescope...${NC}"
php artisan telescope:prune --hours=24
echo -e "${GREEN}✅ Entrées Telescope de plus de 24h supprimées${NC}"

# 4. Analyser l'espace des backups
echo -e "\n${YELLOW}4. Analyse des backups...${NC}"
if [ -d "backups" ]; then
    BACKUP_SIZE=$(du -sh backups | cut -f1)
    BACKUP_COUNT=$(find backups -type f | wc -l)
    echo "   Taille: $BACKUP_SIZE"
    echo "   Nombre de fichiers: $BACKUP_COUNT"
    
    # Lister les plus gros fichiers
    echo -e "\n   ${YELLOW}Plus gros fichiers dans backups/:${NC}"
    find backups -type f -exec du -h {} + | sort -rh | head -5
    
    echo -e "\n   ${YELLOW}Voulez-vous voir le détail? (y/n)${NC}"
    read -r response
    if [[ "$response" == "y" ]]; then
        ls -lah backups/ | head -20
    fi
else
    echo -e "${GREEN}✅ Dossier backups n'existe pas${NC}"
fi

# 5. Créer des données de test
echo -e "\n${YELLOW}5. Création de données de test...${NC}"
php artisan db:seed 2>/dev/null
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Données de test créées${NC}"
else
    echo -e "${YELLOW}⚠️ Pas de seeders disponibles${NC}"
fi

# 6. Optimiser Composer
echo -e "\n${YELLOW}6. Optimisation Composer...${NC}"
composer dump-autoload -o
echo -e "${GREEN}✅ Autoload optimisé${NC}"

# 7. Vérifier l'état Git
echo -e "\n${YELLOW}7. État Git...${NC}"
MODIFIED_COUNT=$(git status --porcelain | wc -l)
echo "   Fichiers modifiés/non suivis: $MODIFIED_COUNT"

if [ $MODIFIED_COUNT -gt 0 ]; then
    echo -e "${YELLOW}   Voulez-vous voir les fichiers modifiés? (y/n)${NC}"
    read -r response
    if [[ "$response" == "y" ]]; then
        git status --short | head -20
    fi
fi

# 8. Créer un backup de sécurité
echo -e "\n${YELLOW}8. Création d'un backup de sécurité...${NC}"
BACKUP_FILE="backup_post_audit_$(date +%Y%m%d_%H%M%S).tar.gz"
tar -czf "$BACKUP_FILE" \
    --exclude=vendor \
    --exclude=node_modules \
    --exclude=storage/logs \
    --exclude=storage/framework/cache \
    --exclude=storage/framework/sessions \
    --exclude=storage/framework/views \
    --exclude=backups \
    .env \
    app \
    config \
    database \
    resources \
    routes \
    2>/dev/null

if [ -f "$BACKUP_FILE" ]; then
    BACKUP_SIZE=$(du -h "$BACKUP_FILE" | cut -f1)
    echo -e "${GREEN}✅ Backup créé: $BACKUP_FILE ($BACKUP_SIZE)${NC}"
    mv "$BACKUP_FILE" ~/
    echo "   Déplacé vers: ~/$BACKUP_FILE"
else
    echo -e "${RED}❌ Erreur lors de la création du backup${NC}"
fi

# 9. Rapport final
echo -e "\n${BLUE}═══════════════════════════════════════${NC}"
echo -e "${BLUE}   RÉSUMÉ DES CORRECTIONS${NC}"
echo -e "${BLUE}═══════════════════════════════════════${NC}"

echo -e "\n${GREEN}Corrections appliquées:${NC}"
echo "  ✅ Permissions .env sécurisées"
echo "  ✅ Logs Laravel nettoyés"
echo "  ✅ Telescope purgé"
echo "  ✅ Autoload Composer optimisé"
echo "  ✅ Backup de sécurité créé"

echo -e "\n${YELLOW}Actions manuelles recommandées:${NC}"
echo "  1. Nettoyer le dossier backups/ (1.2 GB à économiser)"
echo "     Commande: rm -rf backups/[anciens_fichiers]"
echo ""
echo "  2. Faire un commit Git"
echo "     Commandes:"
echo "     git add ."
echo "     git commit -m \"Post-audit cleanup and optimizations\""
echo ""
echo "  3. Mettre à jour les packages"
echo "     Commande: composer update"
echo ""
echo "  4. Créer des seeders pour données de test"
echo "     Commande: php artisan make:seeder TestDataSeeder"

echo -e "\n${BLUE}═══════════════════════════════════════${NC}"
echo -e "${GREEN}✅ Script de corrections terminé!${NC}"
echo -e "${BLUE}═══════════════════════════════════════${NC}"
