#!/bin/bash

# Alias pour StudiosDB Development Tools
# =======================================

echo "# StudiosDB Development Aliases" >> ~/.bashrc
echo "" >> ~/.bashrc

# PHPStan aliases
echo "alias phpstan='vendor/bin/phpstan'" >> ~/.bashrc
echo "alias phpstan-check='vendor/bin/phpstan analyse'" >> ~/.bashrc
echo "alias phpstan-baseline='vendor/bin/phpstan analyse --generate-baseline'" >> ~/.bashrc

# Laravel Backup aliases
echo "alias backup='php artisan backup:run'" >> ~/.bashrc
echo "alias backup-db='php artisan backup:run --only-db'" >> ~/.bashrc
echo "alias backup-files='php artisan backup:run --only-files'" >> ~/.bashrc
echo "alias backup-list='php artisan backup:list'" >> ~/.bashrc
echo "alias backup-clean='php artisan backup:clean'" >> ~/.bashrc

# Telescope aliases
echo "alias telescope-clear='php artisan telescope:clear'" >> ~/.bashrc
echo "alias telescope-prune='php artisan telescope:prune'" >> ~/.bashrc

# StudiosDB aliases
echo "alias sdb='cd /home/studiosdb/studiosunisdb'" >> ~/.bashrc
echo "alias sdb-start='cd /home/studiosdb/studiosunisdb && ./services.sh start all'" >> ~/.bashrc
echo "alias sdb-stop='cd /home/studiosdb/studiosunisdb && ./services.sh stop all'" >> ~/.bashrc
echo "alias sdb-status='cd /home/studiosdb/studiosunisdb && ./monitor.sh health'" >> ~/.bashrc
echo "alias sdb-test='cd /home/studiosdb/studiosunisdb && ./test_dev_tools.sh'" >> ~/.bashrc

echo "" >> ~/.bashrc
echo "# Fonction pour démarrage rapide StudiosDB" >> ~/.bashrc
echo 'sdb-dev() {' >> ~/.bashrc
echo '    cd /home/studiosdb/studiosunisdb' >> ~/.bashrc
echo '    echo "Starting StudiosDB Development Environment..."' >> ~/.bashrc
echo '    ./services.sh start all' >> ~/.bashrc
echo '    echo ""' >> ~/.bashrc
echo '    echo "Services disponibles:"' >> ~/.bashrc
echo '    echo "  • Laravel: http://127.0.0.1:8001"' >> ~/.bashrc
echo '    echo "  • Telescope: http://127.0.0.1:8001/telescope"' >> ~/.bashrc
echo '    echo "  • Vite HMR: http://127.0.0.1:5173"' >> ~/.bashrc
echo '    echo ""' >> ~/.bashrc
echo '    echo "Commandes utiles:"' >> ~/.bashrc
echo '    echo "  • phpstan-check : Analyse statique du code"' >> ~/.bashrc
echo '    echo "  • backup : Créer une sauvegarde complète"' >> ~/.bashrc
echo '    echo "  • sdb-stop : Arrêter tous les services"' >> ~/.bashrc
echo '}' >> ~/.bashrc

echo "✅ Alias ajoutés à ~/.bashrc"
echo ""
echo "Pour activer les alias, exécutez:"
echo "  source ~/.bashrc"
echo ""
echo "Alias disponibles:"
echo "  • sdb         : Aller au dossier StudiosDB"
echo "  • sdb-dev     : Démarrer l'environnement de développement"
echo "  • sdb-start   : Démarrer les services"
echo "  • sdb-stop    : Arrêter les services"
echo "  • sdb-status  : Vérifier l'état du système"
echo "  • sdb-test    : Tester les outils de développement"
echo ""
echo "  • phpstan-check : Analyse PHPStan"
echo "  • backup        : Créer une sauvegarde"
echo "  • telescope-clear : Nettoyer Telescope"
