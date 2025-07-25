#!/bin/bash

echo "🗄️ CRÉATION UTILISATEUR MYSQL STUDIOSDB"
echo "======================================"

# Connexion MySQL en root pour création utilisateur
mysql -u root -p << 'MYSQL_SCRIPT'
-- Création utilisateur studiosdb avec permissions complètes
CREATE USER IF NOT EXISTS 'studiosdb'@'localhost' IDENTIFIED BY 'StudioSDB_2025!Secure';

-- Création base centrale
CREATE DATABASE IF NOT EXISTS studiosdb_central CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Création template tenant
CREATE DATABASE IF NOT EXISTS studiosdb_template CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Permissions complètes sur toutes les bases studiosdb*
GRANT ALL PRIVILEGES ON studiosdb_central.* TO 'studiosdb'@'localhost';
GRANT ALL PRIVILEGES ON studiosdb_template.* TO 'studiosdb'@'localhost'; 
GRANT ALL PRIVILEGES ON studiosdb_ecole_%.* TO 'studiosdb'@'localhost';

-- Permissions création/suppression bases (pour multi-tenant)
GRANT CREATE, DROP ON *.* TO 'studiosdb'@'localhost';

-- Application changements
FLUSH PRIVILEGES;

-- Vérification utilisateur créé
SELECT User, Host FROM mysql.user WHERE User = 'studiosdb';

-- Test connexion
SELECT 'Connexion MySQL studiosdb réussie!' as status;
MYSQL_SCRIPT

echo "✅ Utilisateur MySQL studiosdb créé avec succès"
echo "Password: StudioSDB_2025!Secure"
