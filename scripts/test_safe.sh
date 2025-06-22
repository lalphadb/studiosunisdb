#!/bin/bash
echo "🧪 Tests sécurisés StudiosUnisDB"

# Vérifier l'environnement
if [ "$APP_ENV" = "production" ]; then
    echo "❌ ERREUR: Ne jamais lancer les tests en production!"
    exit 1
fi

# Backup de la DB de dev
if [ -f ".env" ]; then
    echo "💾 Backup de la base de développement..."
    mysqldump -u root -p studiosdb > backup_before_tests_$(date +%Y%m%d_%H%M%S).sql
fi

# Lancer les tests avec env séparé
echo "🧪 Lancement des tests avec base SQLite en mémoire..."
php artisan test --env=testing

echo "✅ Tests terminés - Base de développement intacte!"
