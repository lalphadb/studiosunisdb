#!/bin/bash
echo "🧹 NETTOYAGE COMPLET DU DÉPÔT GIT"
echo "================================"

echo "⚠️  ATTENTION : Cette opération va :"
echo "• Supprimer l'historique Git existant"
echo "• Créer un nouveau dépôt propre"
echo "• Faire un commit initial avec v1.0.0"
echo ""
read -p "Continuer ? (y/N): " confirm

if [[ \$confirm != [yY] ]]; then
    echo "Opération annulée."
    exit 0
fi

# Supprimer .git existant
rm -rf .git

# Initialiser nouveau dépôt
git init
git branch -M main

# Ajouter tous les fichiers
git add .

# Commit initial
git commit -m "feat: version initiale v1.0.0

🚀 StudiosDB - Système de gestion d'écoles de karaté

✨ Fonctionnalités principales:
- Architecture multi-tenant sécurisée
- 7 modules complets (Users, Écoles, Cours, Ceintures, etc.)
- Interface d'administration moderne
- Conformité Loi 25
- Laravel 12.19.3 + Tailwind CSS

📋 Modules inclus:
- 👤 Gestion des utilisateurs
- 🏫 Réseau d'écoles
- 📚 Planning des cours
- 🥋 Système de ceintures
- 🎯 Séminaires
- 💰 Paiements
- ✅ Présences

🛡️ Sécurité:
- Authentification Laravel Breeze
- Permissions Spatie
- Protection CSRF
- Validation complète
- Middleware HasMiddleware (Laravel 12.19)

Version: v1.0.0
Laravel: 12.19.3 LTS
PHP: 8.3.6
Database: MySQL 8.0.42"

# Créer tag de version
git tag -a "v1.0.0" -m "Version 1.0.0 - Release initiale

Première version stable de StudiosDB avec tous les modules fonctionnels.

Highlights:
- Système multi-tenant complet
- 7 modules de gestion
- Interface d'administration moderne
- Conformité réglementaire (Loi 25)
- Architecture Laravel professionnelle

Repository: https://github.com/lalphadb/studiosunisdb"

echo ""
echo "✅ DÉPÔT NETTOYÉ ET PRÊT !"
echo ""
echo "📋 PROCHAINES ÉTAPES :"
echo "1. Configurer le remote GitHub :"
echo "   git remote add origin https://github.com/lalphadb/studiosunisdb.git"
echo ""
echo "2. Pousser le code :"
echo "   git push -u origin main"
echo "   git push origin v1.0.0"
echo ""
echo "3. Créer une release sur GitHub avec le tag v1.0.0"
echo "   https://github.com/lalphadb/studiosunisdb/releases/new"
