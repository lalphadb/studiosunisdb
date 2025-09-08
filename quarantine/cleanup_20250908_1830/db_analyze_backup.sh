#!/bin/bash

# Script d'analyse et de sauvegarde de la base de données MySQL
# Pour projet Laravel - studiosdb

# Configuration
DB_HOST="localhost"
DB_USER="root"  # À modifier selon votre configuration
DB_PASSWORD="LkmP0km1"  # À modifier selon votre configuration
DB_NAME="studiosdb"
LARAVEL_PATH="/home/studiosdb/studiosunisdb/storage/"  # À modifier selon votre projet
BACKUP_DIR="${LARAVEL_PATH}/storage/db_backups"
REPORT_DIR="${LARAVEL_PATH}/storage/db_reports"
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="${BACKUP_DIR}/${DB_NAME}_backup_${DATE}.sql"
REPORT_FILE="${REPORT_DIR}/${DB_NAME}_report_${DATE}.md"

# Couleurs pour le output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Fonction pour afficher les messages d'information
info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

# Fonction pour afficher les messages d'avertissement
warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

# Fonction pour afficher les messages d'erreur
error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Vérification des dépendances
check_dependencies() {
    if ! command -v mysql &> /dev/null; then
        error "MySQL client n'est pas installé"
        exit 1
    fi
    
    if ! command -v mysqldump &> /dev/null; then
        error "mysqldump n'est pas installé"
        exit 1
    fi
}

# Création des répertoires de sauvegarde et de rapports
create_directories() {
    mkdir -p "$BACKUP_DIR"
    mkdir -p "$REPORT_DIR"
    
    if [ ! -d "$BACKUP_DIR" ]; then
        error "Impossible de créer le répertoire de sauvegarde: $BACKUP_DIR"
        exit 1
    fi
    
    if [ ! -d "$REPORT_DIR" ]; then
        error "Impossible de créer le répertoire de rapports: $REPORT_DIR"
        exit 1
    fi
}

# Sauvegarde de la base de données
backup_database() {
    info "Sauvegarde de la base de données ${DB_NAME}..."
    
    if [ -z "$DB_PASSWORD" ]; then
        mysqldump -h $DB_HOST -u $DB_USER $DB_NAME > $BACKUP_FILE
    else
        mysqldump -h $DB_HOST -u $DB_USER -p$DB_PASSWORD $DB_NAME > $BACKUP_FILE
    fi
    
    if [ $? -eq 0 ]; then
        info "Sauvegarde terminée: $BACKUP_FILE"
        
        # Compression de la sauvegarde
        gzip $BACKUP_FILE
        info "Sauvegarde compressée: ${BACKUP_FILE}.gz"
    else
        error "Échec de la sauvegarde de la base de données"
        exit 1
    fi
}

# Génération du rapport d'analyse
generate_report() {
    info "Génération du rapport d'analyse..."
    
    # Début du rapport
    echo "# Rapport d'analyse de la base de données ${DB_NAME}" > $REPORT_FILE
    echo "Date: $(date)" >> $REPORT_FILE
    echo "" >> $REPORT_FILE
    
    # 1. Schéma global
    echo "## 1. Schéma Global" >> $REPORT_FILE
    echo "" >> $REPORT_FILE
    echo "### Bases de données disponibles:" >> $REPORT_FILE
    echo '```sql' >> $REPORT_FILE
    mysql -h $DB_HOST -u $DB_USER -p$DB_PASSWORD -e "SHOW DATABASES;" >> $REPORT_FILE 2>/dev/null
    echo '```' >> $REPORT_FILE
    echo "" >> $REPORT_FILE
    
    # 2. Liste des tables
    echo "### Tables de la base ${DB_NAME}:" >> $REPORT_FILE
    echo '```sql' >> $REPORT_FILE
    mysql -h $DB_HOST -u $DB_USER -p$DB_PASSWORD -D $DB_NAME -e "SHOW TABLES;" >> $REPORT_FILE 2>/dev/null
    echo '```' >> $REPORT_FILE
    echo "" >> $REPORT_FILE
    
    # 3. Structure détaillée de chaque table
    echo "## 2. Structure des Tables" >> $REPORT_FILE
    echo "" >> $REPORT_FILE
    
    TABLES=$(mysql -h $DB_HOST -u $DB_USER -p$DB_PASSWORD -D $DB_NAME -e "SHOW TABLES;" -s 2>/dev/null)
    
    for TABLE in $TABLES; do
        echo "### Table: $TABLE" >> $REPORT_FILE
        echo "" >> $REPORT_FILE
        
        # Structure de la table
        echo "#### Structure:" >> $REPORT_FILE
        echo '```sql' >> $REPORT_FILE
        mysql -h $DB_HOST -u $DB_USER -p$DB_PASSWORD -D $DB_NAME -e "DESCRIBE $TABLE;" >> $REPORT_FILE 2>/dev/null
        echo '```' >> $REPORT_FILE
        echo "" >> $REPORT_FILE
        
        # Commandes SQL de création de la table
        echo "#### Commandes de création:" >> $REPORT_FILE
        echo '```sql' >> $REPORT_FILE
        mysql -h $DB_HOST -u $DB_USER -p$DB_PASSWORD -D $DB_NAME -e "SHOW CREATE TABLE $TABLE\G" >> $REPORT_FILE 2>/dev/null
        echo '```' >> $REPORT_FILE
        echo "" >> $REPORT_FILE
    done
    
    # 4. Relations et clés étrangères
    echo "## 3. Relations et Clés Étrangères" >> $REPORT_FILE
    echo "" >> $REPORT_FILE
    echo '```sql' >> $REPORT_FILE
    mysql -h $DB_HOST -u $DB_USER -p$DB_PASSWORD -D information_schema -e "
    SELECT 
        TABLE_NAME,
        COLUMN_NAME,
        CONSTRAINT_NAME,
        REFERENCED_TABLE_NAME,
        REFERENCED_COLUMN_NAME
    FROM KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = '${DB_NAME}'
    AND REFERENCED_TABLE_NAME IS NOT NULL;" >> $REPORT_FILE 2>/dev/null
    echo '```' >> $REPORT_FILE
    echo "" >> $REPORT_FILE
    
    # 5. Métriques techniques
    echo "## 4. Métriques Techniques" >> $REPORT_FILE
    echo "" >> $REPORT_FILE
    echo "### Taille des tables:" >> $REPORT_FILE
    echo '```sql' >> $REPORT_FILE
    mysql -h $DB_HOST -u $DB_USER -p$DB_PASSWORD -D information_schema -e "
    SELECT 
        TABLE_NAME AS \`Table\`,
        ROUND((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024, 2) AS \`Size (MB)\`,
        TABLE_ROWS AS \`Rows\`
    FROM TABLES
    WHERE TABLE_SCHEMA = '${DB_NAME}'
    ORDER BY (DATA_LENGTH + INDEX_LENGTH) DESC;" >> $REPORT_FILE 2>/dev/null
    echo '```' >> $REPORT_FILE
    echo "" >> $REPORT_FILE
    
    # 6. Index et performances
    echo "## 5. Index et Performances" >> $REPORT_FILE
    echo "" >> $REPORT_FILE
    
    for TABLE in $TABLES; do
        echo "### Index de la table: $TABLE" >> $REPORT_FILE
        echo '```sql' >> $REPORT_FILE
        mysql -h $DB_HOST -u $DB_USER -p$DB_PASSWORD -D $DB_NAME -e "SHOW INDEX FROM $TABLE;" >> $REPORT_FILE 2>/dev/null
        echo '```' >> $REPORT_FILE
        echo "" >> $REPORT_FILE
    done
    
    info "Rapport généré: $REPORT_FILE"
}

# Nettoyage des anciens fichiers (garder les 7 derniers jours)
clean_old_files() {
    info "Nettoyage des sauvegardes et rapports de plus de 7 jours..."
    find $BACKUP_DIR -name "*.sql.gz" -mtime +7 -exec rm -f {} \;
    find $REPORT_DIR -name "*.md" -mtime +7 -exec rm -f {} \;
}

# Fonction principale
main() {
    info "Début de l'analyse et de la sauvegarde de la base de données ${DB_NAME}"
    
    check_dependencies
    create_directories
    backup_database
    generate_report
    clean_old_files
    
    info "Processus terminé avec succès!"
    info "Sauvegarde: ${BACKUP_FILE}.gz"
    info "Rapport: ${REPORT_FILE}"
}

# Exécution du script
main
