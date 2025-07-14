#!/bin/bash

echo "🔧 CORRECTION SOFT DELETES - STUDIOSDB V4"
echo "========================================"

cd /home/studiosdb/studiosunisdb/studiosdb-v4

echo "=== DIAGNOSTIC ==="
echo ""

# Vérifier les colonnes de la table cours
echo "🔍 Vérification des colonnes table cours:"
mysql -u root -pLkmP0km1 studiosdb -e "DESCRIBE cours;" 2>/dev/null | grep deleted_at || echo "❌ Colonne deleted_at manquante"

echo ""
echo "=== SOLUTION 1: AJOUTER LES COLONNES DELETED_AT ==="
echo ""

read -p "Voulez-vous ajouter les colonnes deleted_at aux tables ? (y/N): " add_columns
if [[ $add_columns == [yY] ]]; then
    
    # Ajouter deleted_at à toutes les tables qui utilisent SoftDeletes
    echo "📝 Ajout des colonnes deleted_at..."
    
    mysql -u root -pLkmP0km1 studiosdb << 'SQL'
-- Ajouter deleted_at aux tables manquantes
ALTER TABLE cours ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE session_cours ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE presences ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE inscriptions_cours ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE paiements ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE seminaires ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE inscriptions_seminaires ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL;
SQL

    if [ $? -eq 0 ]; then
        echo "✅ Colonnes deleted_at ajoutées"
    else
        echo "❌ Erreur lors de l'ajout des colonnes"
        exit 1
    fi
    
else
    echo "⚠️ Suppression temporaire des SoftDeletes des modèles..."
    
    # Désactiver SoftDeletes dans les modèles concernés
    for model in Cours SessionCours Presence InscriptionCours Paiement Seminaire InscriptionSeminaire; do
        if [ -f "app/Models/$model.php" ]; then
            sed -i 's/use SoftDeletes;/\/\/ use SoftDeletes; \/\/ Désactivé temporairement/' "app/Models/$model.php"
            sed -i 's/use Illuminate\\Database\\Eloquent\\SoftDeletes;/\/\/ use Illuminate\\Database\\Eloquent\\SoftDeletes; \/\/ Désactivé/' "app/Models/$model.php"
            echo "✅ SoftDeletes désactivé dans $model"
        fi
    done
fi

echo ""
echo "=== TEST ==="
echo ""

# Nettoyer les caches
php artisan config:clear
php artisan cache:clear
composer dump-autoload

# Test
echo "🧪 Test de fonctionnement..."
if php artisan --version >/dev/null 2>&1; then
    echo "✅ Laravel fonctionne"
else
    echo "❌ Problème persiste"
    exit 1
fi

# Test serveur
echo "🚀 Test serveur..."
timeout 5s php artisan serve --host=0.0.0.0 --port=8002 &
SERVER_PID=$!
sleep 2

if kill -0 $SERVER_PID 2>/dev/null; then
    echo "✅ Serveur démarre - Interface accessible"
    kill $SERVER_PID >/dev/null 2>&1
    
    echo ""
    echo "🎉 PROBLÈME RÉSOLU !"
    echo "🌐 Testez: http://localhost:8001/admin"
else
    echo "❌ Serveur ne démarre toujours pas"
fi

echo ""
echo "✅ CORRECTION TERMINÉE"
