#!/bin/bash

DB_USER="root"
DB_PASS="[VOIR_ENV_FILE]"
DB_NAME="studiosdb"

echo "=========================================="
echo "ANALYSE COMPLÈTE DE LA BASE: $DB_NAME"
echo "=========================================="

# Lister les tables
echo -e "\n🗃️  TABLES DISPONIBLES:"
mysql -u $DB_USER -p$DB_PASS -e "USE $DB_NAME; SHOW TABLES;"

# Structure de chaque table
echo -e "\n📋 STRUCTURE DES TABLES:"
mysql -u $DB_USER -p$DB_PASS -e "
USE $DB_NAME;
SELECT 
    TABLE_NAME as 'Table',
    COLUMN_NAME as 'Colonne',
    DATA_TYPE as 'Type',
    IS_NULLABLE as 'NULL',
    COLUMN_KEY as 'Clé',
    EXTRA as 'Extra'
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = '$DB_NAME' 
ORDER BY TABLE_NAME, ORDINAL_POSITION;"

# Relations
echo -e "\n🔗 RELATIONS ENTRE TABLES:"
mysql -u $DB_USER -p$DB_PASS -e "
USE $DB_NAME;
SELECT 
    CONCAT(TABLE_NAME, '.', COLUMN_NAME) as 'Source',
    CONCAT(REFERENCED_TABLE_NAME, '.', REFERENCED_COLUMN_NAME) as 'Référence',
    CONSTRAINT_NAME as 'Contrainte'
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE REFERENCED_TABLE_SCHEMA = '$DB_NAME'
AND REFERENCED_TABLE_NAME IS NOT NULL;"

# Contenu de chaque table
echo -e "\n📊 CONTENU DES TABLES:"
for table in $(mysql -u $DB_USER -p$DB_PASS -e "USE $DB_NAME; SHOW TABLES;" | tail -n +2); do
    echo -e "\n--- Contenu de la table: $table ---"
    mysql -u $DB_USER -p$DB_PASS -e "USE $DB_NAME; SELECT * FROM $table LIMIT 10;" 2>/dev/null || echo "Erreur lors de la lecture de $table"
done

echo -e "\n✅ Analyse terminée!"
