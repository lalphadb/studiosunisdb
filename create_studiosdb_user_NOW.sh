#!/bin/bash

echo "🗄️ CRÉATION UTILISATEUR MYSQL STUDIOSDB MAINTENANT"
echo "================================================="

# Méthode 1: Via commande directe
echo "Création utilisateur studiosdb..."

mysql -u root -p << 'MYSQL_EOF'
-- Suppression utilisateur s'il existe déjà (au cas où)
DROP USER IF EXISTS 'studiosdb'@'localhost';

-- Création utilisateur avec mot de passe sécurisé
CREATE USER 'studiosdb'@'localhost' IDENTIFIED BY 'StudioSDB_2025!Secure';

-- Création bases de données StudiosDB
CREATE DATABASE IF NOT EXISTS studiosdb_central CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE IF NOT EXISTS studiosdb_template CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Attribution permissions COMPLÈTES
GRANT ALL PRIVILEGES ON studiosdb_central.* TO 'studiosdb'@'localhost';
GRANT ALL PRIVILEGES ON studiosdb_template.* TO 'studiosdb'@'localhost';
GRANT ALL PRIVILEGES ON `studiosdb_ecole_%`.* TO 'studiosdb'@'localhost';

-- Permissions création/suppression bases pour multi-tenant
GRANT CREATE, DROP ON *.* TO 'studiosdb'@'localhost';

-- Application immédiate
FLUSH PRIVILEGES;

-- Vérification création
SELECT User, Host FROM mysql.user WHERE User = 'studiosdb';
SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME LIKE 'studiosdb%';

-- Test connexion
SELECT 'UTILISATEUR STUDIOSDB CRÉÉ AVEC SUCCÈS!' as message;
MYSQL_EOF

echo ""
echo "✅ Utilisateur MySQL studiosdb créé!"
echo "Username: studiosdb"
echo "Password: StudioSDB_2025!Secure"
echo "Databases: studiosdb_central, studiosdb_template"
