#!/bin/bash
echo "🔍 Vérification StudiosUnisDB"
echo "=========================="

# Base de données
if mysql -u root -pLkmP0km1 -e "USE studiosdb;" >/dev/null 2>&1; then
    echo "✅ Base de données accessible"
    
    # Compter les tables
    TABLES=$(mysql -u root -pLkmP0km1 -D studiosdb -e "SHOW TABLES;" | wc -l)
    echo "✅ Tables: $((TABLES-1))"
    
    # Compter les utilisateurs
    USERS=$(mysql -u root -pLkmP0km1 -D studiosdb -e "SELECT COUNT(*) FROM users;" -s)
    echo "✅ Utilisateurs: $USERS"
    
    # Compter les écoles
    ECOLES=$(mysql -u root -pLkmP0km1 -D studiosdb -e "SELECT COUNT(*) FROM ecoles;" -s)
    echo "✅ Écoles: $ECOLES"
    
else
    echo "❌ Problème base de données"
fi

echo "🎉 Vérification terminée"
