#!/bin/bash

# StudiosDB Quick Start
# Purpose: Corrige et lance automatiquement l'application

echo "=== StudiosDB Quick Start ==="
echo ""

# Run fix script first
echo "Étape 1: Correction automatique des problèmes..."
bash fix-app.sh

echo ""
echo "Étape 2: Démarrage du serveur..."
echo ""

# Ask user for mode
echo "Choisissez le mode de démarrage:"
echo "1) Production (assets compilés, plus rapide)"
echo "2) Développement (hot reload, pour coder)"
echo -n "Votre choix [1]: "
read -t 10 choice

# Default to production if no input
choice=${choice:-1}

case $choice in
    2)
        echo "Démarrage en mode développement..."
        bash start-dev.sh
        ;;
    *)
        echo "Démarrage en mode production..."
        bash start-server.sh
        ;;
esac
