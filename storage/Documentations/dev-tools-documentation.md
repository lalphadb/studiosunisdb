# 📚 Documentation des Outils de Développement StudiosDB

## 🎯 Vue d'ensemble

Tous les outils de développement avancés ont été installés et configurés pour votre projet StudiosDB v6.

### ✅ Outils installés

| Outil | Version | Statut | Description |
|-------|---------|--------|-------------|
| **Laravel Telescope** | 5.10.2 | ✅ Installé | Débogage et monitoring avancé |
| **Laravel Debugbar** | 3.16.0 | ✅ Installé | Barre de débogage en temps réel |
| **PHPStan** | 2.1.22 | ✅ Installé | Analyse statique du code PHP |
| **Laravel Backup** | 9.3.4 | ✅ Installé | Sauvegardes automatiques |

---

## 🔬 Laravel Telescope

### Description
Telescope est un assistant de débogage élégant pour Laravel qui fournit des insights sur les requêtes, exceptions, logs, queries de base de données, jobs en queue, mail, notifications, cache, et plus.

### Accès
```bash
http://127.0.0.1:8001/telescope
```

### Fonctionnalités principales
- **Requests** : Historique de toutes les requêtes HTTP
- **Commands** : Commandes Artisan exécutées
- **Schedule** : Tâches planifiées
- **Jobs** : Jobs en queue
- **Exceptions** : Erreurs et exceptions
- **Logs** : Messages de log
- **Dumps** : Variables dumpées
- **Queries** : Requêtes SQL avec temps d'exécution
- **Models** : Événements Eloquent
- **Events** : Événements Laravel
- **Mail** : Emails envoyés
- **Notifications** : Notifications envoyées
- **Cache** : Opérations de cache
- **Redis** : Commandes Redis

### Commandes utiles
```bash
# Nettoyer les entrées Telescope
php artisan telescope:clear

# Supprimer les entrées de plus de 24h
php artisan telescope:prune

# Supprimer les entrées de plus de 48h
php artisan telescope:prune --hours=48
```

### Configuration
Fichier : `config/telescope.php`

Pour désactiver en production :
```php
// Dans AppServiceProvider
public function register()
{
    if ($this->app->environment('local')) {
        $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
    }
}
```

---

## 🐛 Laravel Debugbar

### Description
Affiche une barre de débogage en bas de page avec des informations détaillées sur l'exécution.

### Activation
Automatique quand `APP_DEBUG=true` dans `.env`

### Panneaux disponibles
- **Messages** : Messages de debug
- **Timeline** : Chronologie d'exécution
- **Exceptions** : Erreurs capturées
- **Views** : Vues rendues avec données
- **Route** : Information sur la route
- **Queries** : Requêtes SQL
- **Models** : Modèles Eloquent chargés
- **Session** : Données de session
- **Request** : Données de la requête

### Configuration
```php
// Désactiver temporairement
\Debugbar::disable();

// Ajouter des messages
\Debugbar::info('Info message');
\Debugbar::error('Error message');
\Debugbar::warning('Warning message');

// Mesurer le temps
\Debugbar::startMeasure('render','Time to render');
// ... code ...
\Debugbar::stopMeasure('render');

// Ajouter une exception
try {
    // code
} catch (\Exception $e) {
    \Debugbar::addThrowable($e);
}
```

---

## 📊 PHPStan

### Description
Outil d'analyse statique qui trouve les bugs dans votre code sans l'exécuter.

### Configuration
Fichier : `phpstan.neon`

### Niveaux d'analyse (0-9)
- **Level 0** : Erreurs basiques (classes non trouvées, méthodes inconnues)
- **Level 1** : Variables indéfinies
- **Level 2** : Méthodes inconnues sur $this
- **Level 3** : Types de retour
- **Level 4** : Dead code
- **Level 5** : Types de paramètres (recommandé)
- **Level 6** : Types de retour manquants
- **Level 7** : Propriétés manquantes
- **Level 8** : Appels de méthodes sur nullable
- **Level 9** : Mode strict maximal

### Commandes
```bash
# Analyse simple
vendor/bin/phpstan analyse

# Analyse avec niveau spécifique
vendor/bin/phpstan analyse --level=5

# Analyse d'un dossier spécifique
vendor/bin/phpstan analyse app/Http/Controllers

# Générer une baseline (ignorer les erreurs existantes)
vendor/bin/phpstan analyse --generate-baseline

# Analyse avec baseline
vendor/bin/phpstan analyse --configuration=phpstan.neon
```

### Exemples d'erreurs détectées
- Variables non définies
- Méthodes appelées sur null
- Types de retour incorrects
- Paramètres manquants
- Dead code
- Conditions toujours vraies/fausses

---

## 💾 Laravel Backup

### Description
Package complet pour sauvegarder votre application (base de données + fichiers).

### Configuration
Fichier : `config/backup.php`

### Commandes principales
```bash
# Sauvegarde complète (DB + fichiers)
php artisan backup:run

# Sauvegarde base de données uniquement
php artisan backup:run --only-db

# Sauvegarde fichiers uniquement
php artisan backup:run --only-files

# Lister les sauvegardes
php artisan backup:list

# Nettoyer les vieilles sauvegardes
php artisan backup:clean

# Surveiller la santé des sauvegardes
php artisan backup:monitor
```

### Configuration recommandée
```php
// config/backup.php
'source' => [
    'files' => [
        'include' => [
            base_path(),
        ],
        'exclude' => [
            base_path('vendor'),
            base_path('node_modules'),
            storage_path(),
            base_path('.git'),
        ],
    ],
],

'destination' => [
    'disks' => ['local'],
],

'cleanup' => [
    'strategy' => \Spatie\Backup\Tasks\Cleanup\Strategies\DefaultStrategy::class,
    'default_strategy' => [
        'keep_all_backups_for_days' => 7,
        'keep_daily_backups_for_days' => 16,
        'keep_weekly_backups_for_weeks' => 8,
        'keep_monthly_backups_for_months' => 4,
        'keep_yearly_backups_for_years' => 2,
    ],
],
```

### Automatisation avec Cron
```bash
# Ajouter dans crontab
0 2 * * * cd /home/studiosdb/studiosunisdb && php artisan backup:run --only-db >> /dev/null 2>&1
0 3 * * 0 cd /home/studiosdb/studiosunisdb && php artisan backup:run >> /dev/null 2>&1
0 4 * * * cd /home/studiosdb/studiosunisdb && php artisan backup:clean >> /dev/null 2>&1
```

---

## 🚀 Workflow de développement recommandé

### 1. Démarrage quotidien
```bash
# Démarrer l'environnement
sdb-dev

# Ou manuellement
cd /home/studiosdb/studiosunisdb
./services.sh start all
```

### 2. Pendant le développement
- **Debugbar** : Visible automatiquement en bas de page
- **Telescope** : Consulter régulièrement pour les erreurs
- **PHPStan** : Avant chaque commit

### 3. Avant un commit
```bash
# Analyse PHPStan
vendor/bin/phpstan analyse --level=5

# Tests unitaires
php artisan test

# Vérifier les logs
php artisan telescope:prune
```

### 4. Sauvegarde régulière
```bash
# Sauvegarde quotidienne de la DB
php artisan backup:run --only-db

# Sauvegarde hebdomadaire complète
php artisan backup:run
```

---

## 📝 Scripts utilitaires créés

### `/test_dev_tools.sh`
Test l'installation de tous les outils

### `/setup_aliases.sh`
Configure les alias bash pour un accès rapide

### `/mysql_helper.php`
Gestion de la base de données

### `/monitor.sh`
Monitoring du système

### `/services.sh`
Gestion des services Laravel/Vite

### `/deploy.sh`
Déploiement automatisé

---

## 🔧 Dépannage

### Telescope ne s'affiche pas
```bash
# Vérifier les migrations
php artisan telescope:install
php artisan migrate

# Nettoyer le cache
php artisan cache:clear
php artisan config:clear
```

### Debugbar ne s'affiche pas
```bash
# Vérifier APP_DEBUG dans .env
APP_DEBUG=true

# Publier les assets
php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"
```

### Erreurs PHPStan
```bash
# Générer une baseline pour ignorer les erreurs existantes
vendor/bin/phpstan analyse --generate-baseline

# Augmenter la mémoire si nécessaire
vendor/bin/phpstan analyse --memory-limit=1G
```

### Backup échoue
```bash
# Vérifier l'espace disque
df -h

# Vérifier les permissions
chmod -R 755 storage/app/backups

# Tester manuellement
php artisan backup:run --only-db --disable-notifications
```

---

## 📚 Ressources

- [Laravel Telescope Documentation](https://laravel.com/docs/telescope)
- [Laravel Debugbar GitHub](https://github.com/barryvdh/laravel-debugbar)
- [PHPStan Documentation](https://phpstan.org/user-guide/getting-started)
- [Laravel Backup Documentation](https://spatie.be/docs/laravel-backup)

---

## ✨ Tips & Tricks

### Telescope
- Utilisez les tags pour filtrer les entrées
- Configurez la période de rétention pour économiser l'espace
- Désactivez les watchers non utilisés pour améliorer les performances

### Debugbar
- Utilisez `Clockwork` comme alternative si Debugbar est trop lourd
- Personnalisez les collectors dans `config/debugbar.php`

### PHPStan
- Commencez avec level 0 et augmentez progressivement
- Utilisez les annotations PHPDoc pour aider PHPStan
- Créez des stubs pour les méthodes magiques

### Backup
- Testez régulièrement la restauration
- Utilisez plusieurs destinations (local, S3, etc.)
- Configurez les notifications par email/Slack

---

## 🎉 Conclusion

Votre environnement de développement StudiosDB est maintenant équipé d'outils professionnels pour :
- 🔍 **Déboguer** efficacement (Telescope, Debugbar)
- 📊 **Analyser** la qualité du code (PHPStan)
- 💾 **Sauvegarder** automatiquement (Laravel Backup)
- 🚀 **Déployer** en toute sécurité

Bon développement ! 🚀
