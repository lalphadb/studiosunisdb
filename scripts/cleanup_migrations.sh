#!/bin/bash

# Script de nettoyage des migrations fantômes
# Version: 1.0.0
# Date: 2025-09-01

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}╔══════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║          NETTOYAGE MIGRATIONS FANTÔMES STUDIOSDB          ║${NC}"
echo -e "${BLUE}╚══════════════════════════════════════════════════════════╝${NC}"
echo ""

cd /home/studiosdb/studiosunisdb

# 1. Backup de sécurité
echo -e "${YELLOW}▶ Création backup de sécurité...${NC}"
BACKUP_FILE="/home/studiosdb/backup_migrations_$(date +%Y%m%d_%H%M%S).sql"
mysqldump -u root -pLkmP0km1 studiosdb migrations > "$BACKUP_FILE" 2>/dev/null
echo -e "${GREEN}✓ Backup créé: $BACKUP_FILE${NC}"

# 2. Lister les migrations fantômes
echo -e "\n${YELLOW}▶ Migrations fantômes à nettoyer:${NC}"
echo "• 2025_08_18_000001_add_ecole_id_core"
echo "• 2025_08_21_000000_create_ecoles_table"
echo "• 2025_08_21_200000_create_ecoles_table" 
echo "• 2025_08_29_164000_add_deleted_at_to_cours_table"

# 3. Confirmation
echo ""
read -p "Supprimer ces migrations fantômes de la DB? (o/N) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Oo]$ ]]; then
    echo -e "${YELLOW}⚠ Annulé par l'utilisateur${NC}"
    exit 0
fi

# 4. Suppression des migrations fantômes
echo -e "\n${YELLOW}▶ Suppression des migrations fantômes...${NC}"

php artisan tinker --execute="
    use Illuminate\Support\Facades\DB;
    
    \$phantoms = [
        '2025_08_18_000001_add_ecole_id_core',
        '2025_08_21_000000_create_ecoles_table',
        '2025_08_21_200000_create_ecoles_table',
        '2025_08_29_164000_add_deleted_at_to_cours_table'
    ];
    
    foreach (\$phantoms as \$migration) {
        \$deleted = DB::table('migrations')
            ->where('migration', \$migration)
            ->delete();
        
        if (\$deleted) {
            echo \"✓ Supprimé: {\$migration}\" . PHP_EOL;
        } else {
            echo \"⚠ Non trouvé: {\$migration}\" . PHP_EOL;
        }
    }
"

# 5. Vérification
echo -e "\n${YELLOW}▶ Vérification après nettoyage:${NC}"
PENDING=$(php artisan migrate:status | grep "Pending" | wc -l)
echo -e "${GREEN}✓ Migrations en attente: $PENDING${NC}"

echo -e "\n${GREEN}✅ Nettoyage terminé!${NC}"
echo "Backup disponible: $BACKUP_FILE"
