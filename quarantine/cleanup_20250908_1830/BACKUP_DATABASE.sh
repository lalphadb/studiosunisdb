#!/bin/bash
echo "=== SAUVEGARDE BASE DE DONNÉES COMPLÈTE ==="
cd /home/studiosdb/studiosunisdb

TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="$1" # Répertoire de backup passé en paramètre

if [ -z "$BACKUP_DIR" ]; then
    BACKUP_DIR="backups/db_backup_${TIMESTAMP}"
    mkdir -p "$BACKUP_DIR"
fi

mkdir -p "${BACKUP_DIR}/database_dumps"

echo "💾 DUMP BASE DE DONNÉES"
echo "📁 Destination: ${BACKUP_DIR}/database_dumps/"

# Récupérer les paramètres de connexion depuis .env
DB_HOST=$(grep DB_HOST .env 2>/dev/null | cut -d '=' -f2 | tr -d ' "' || echo "localhost")
DB_PORT=$(grep DB_PORT .env 2>/dev/null | cut -d '=' -f2 | tr -d ' "' || echo "3306")
DB_DATABASE=$(grep DB_DATABASE .env 2>/dev/null | cut -d '=' -f2 | tr -d ' "')
DB_USERNAME=$(grep DB_USERNAME .env 2>/dev/null | cut -d '=' -f2 | tr -d ' "')
DB_PASSWORD=$(grep DB_PASSWORD .env 2>/dev/null | cut -d '=' -f2 | tr -d ' "')

echo "🔧 Paramètres DB:"
echo "- Host: ${DB_HOST}"
echo "- Port: ${DB_PORT}"
echo "- Database: ${DB_DATABASE}"
echo "- User: ${DB_USERNAME}"

# Dump structure seule (pour référence)
echo ""
echo "📋 DUMP STRUCTURE SEULE..."
if command -v mysqldump >/dev/null 2>&1; then
    mysqldump -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USERNAME}" -p"${DB_PASSWORD}" \
        --no-data --routines --triggers \
        "${DB_DATABASE}" > "${BACKUP_DIR}/database_dumps/structure_only_${TIMESTAMP}.sql" 2>/dev/null
    
    if [ $? -eq 0 ]; then
        echo "✅ Structure sauvegardée"
        STRUCTURE_SIZE=$(ls -lah "${BACKUP_DIR}/database_dumps/structure_only_${TIMESTAMP}.sql" | awk '{print $5}')
        echo "   Taille: ${STRUCTURE_SIZE}"
    else
        echo "❌ Erreur dump structure"
    fi
else
    echo "⚠️  mysqldump non disponible, utilisation alternative Laravel"
    # Alternative avec Laravel schema
    php artisan tinker --execute="
    echo '-- Structure Tables StudiosDB' . PHP_EOL;
    echo '-- Generated: ' . date('Y-m-d H:i:s') . PHP_EOL;
    echo PHP_EOL;
    
    \$tables = DB::select('SHOW TABLES');
    foreach (\$tables as \$table) {
        \$tableName = array_values((array)\$table)[0];
        echo '-- Table: ' . \$tableName . PHP_EOL;
        
        try {
            \$createTable = DB::select('SHOW CREATE TABLE ' . \$tableName)[0];
            \$createSQL = array_values((array)\$createTable)[1];
            echo \$createSQL . ';' . PHP_EOL . PHP_EOL;
        } catch (Exception \$e) {
            echo '-- Erreur: ' . \$e->getMessage() . PHP_EOL . PHP_EOL;
        }
    }
    " > "${BACKUP_DIR}/database_dumps/structure_laravel_${TIMESTAMP}.sql" 2>/dev/null
    echo "✅ Structure sauvegardée (Laravel)"
fi

# Dump données complètes
echo ""
echo "📊 DUMP DONNÉES COMPLÈTES..."
if command -v mysqldump >/dev/null 2>&1; then
    mysqldump -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USERNAME}" -p"${DB_PASSWORD}" \
        --routines --triggers --single-transaction \
        "${DB_DATABASE}" > "${BACKUP_DIR}/database_dumps/complete_data_${TIMESTAMP}.sql" 2>/dev/null
    
    if [ $? -eq 0 ]; then
        echo "✅ Données complètes sauvegardées"
        DATA_SIZE=$(ls -lah "${BACKUP_DIR}/database_dumps/complete_data_${TIMESTAMP}.sql" | awk '{print $5}')
        echo "   Taille: ${DATA_SIZE}"
    else
        echo "❌ Erreur dump données"
    fi
else
    echo "⚠️  Dump MySQL non disponible, sauvegarde partielle"
fi

# Export spécifique des données critiques via Laravel
echo ""
echo "📋 EXPORT DONNÉES CRITIQUES (Laravel)..."

# Users
php artisan tinker --execute="
try {
    \$users = App\\Models\\User::all();
    echo 'USERS (' . \$users->count() . '):' . PHP_EOL;
    foreach (\$users as \$user) {
        echo '- ID:' . \$user->id . ' | ' . \$user->name . ' | ' . \$user->email . PHP_EOL;
    }
    echo PHP_EOL;
} catch (Exception \$e) {
    echo 'Erreur Users: ' . \$e->getMessage() . PHP_EOL;
}
" > "${BACKUP_DIR}/database_dumps/critical_data_${TIMESTAMP}.txt" 2>/dev/null

# Cours
php artisan tinker --execute="
try {
    if (class_exists('App\\\\Models\\\\Cours')) {
        \$cours = App\\Models\\Cours::all();
        echo 'COURS (' . \$cours->count() . '):' . PHP_EOL;
        foreach (\$cours as \$c) {
            echo '- ID:' . \$c->id . ' | ' . \$c->nom . ' | ' . \$c->niveau . ' | Actif:' . (\$c->actif ? 'OUI' : 'NON') . PHP_EOL;
        }
        echo PHP_EOL;
    } else {
        echo 'Model Cours non trouvé' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Erreur Cours: ' . \$e->getMessage() . PHP_EOL;
}
" >> "${BACKUP_DIR}/database_dumps/critical_data_${TIMESTAMP}.txt" 2>/dev/null

# Membres
php artisan tinker --execute="
try {
    if (class_exists('App\\\\Models\\\\Membre')) {
        \$membres = App\\Models\\Membre::all();
        echo 'MEMBRES (' . \$membres->count() . '):' . PHP_EOL;
        foreach (\$membres as \$m) {
            echo '- ID:' . \$m->id . ' | ' . (\$m->prenom ?? 'N/A') . ' ' . (\$m->nom ?? 'N/A') . ' | Actif:' . (\$m->actif ? 'OUI' : 'NON') . PHP_EOL;
        }
        echo PHP_EOL;
    } else {
        echo 'Model Membre non trouvé' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Erreur Membres: ' . \$e->getMessage() . PHP_EOL;
}
" >> "${BACKUP_DIR}/database_dumps/critical_data_${TIMESTAMP}.txt" 2>/dev/null

# Roles et permissions
php artisan tinker --execute="
try {
    if (class_exists('Spatie\\\\Permission\\\\Models\\\\Role')) {
        echo 'ROLES & PERMISSIONS:' . PHP_EOL;
        \$roles = Spatie\\Permission\\Models\\Role::with('permissions')->get();
        foreach (\$roles as \$role) {
            echo '- Role: ' . \$role->name . PHP_EOL;
            foreach (\$role->permissions as \$perm) {
                echo '  * ' . \$perm->name . PHP_EOL;
            }
        }
        echo PHP_EOL;
    } else {
        echo 'Spatie Permission non configuré' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Erreur Permissions: ' . \$e->getMessage() . PHP_EOL;
}
" >> "${BACKUP_DIR}/database_dumps/critical_data_${TIMESTAMP}.txt" 2>/dev/null

echo "✅ Données critiques exportées"

echo ""
echo "📋 RÉSUMÉ SAUVEGARDE DB:"
echo "📁 Répertoire: ${BACKUP_DIR}/database_dumps/"
ls -lah "${BACKUP_DIR}/database_dumps/" 2>/dev/null | tail -n +2

echo ""
echo "✅ SAUVEGARDE BASE DE DONNÉES TERMINÉE"

# Instructions de restauration
cat > "${BACKUP_DIR}/database_dumps/RESTORE_INSTRUCTIONS.md" << 'EOF'
# Instructions Restauration Base de Données

## Restauration complète
```bash
# Avec mysqldump
mysql -h HOST -P PORT -u USER -p DATABASE_NAME < complete_data_TIMESTAMP.sql

# Ou structure seule
mysql -h HOST -P PORT -u USER -p DATABASE_NAME < structure_only_TIMESTAMP.sql
```

## Vérification Laravel
```bash
php artisan migrate:status
php artisan tinker --execute="echo 'Users: ' . App\Models\User::count();"
```

## Données critiques
Consultez `critical_data_TIMESTAMP.txt` pour un résumé des données importantes.
EOF

echo "📖 Instructions de restauration créées"
