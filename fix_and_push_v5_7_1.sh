#!/bin/bash
echo "🚀 NETTOYAGE ET VERSION v5.7.1"
echo "=============================="

echo "📋 Version selon votre système :"
echo "• v5.7.1 = 5 versions créées, 7 modules, 1 ajustement"
echo ""

# Supprimer l'origine existante et recréer
git remote remove origin 2>/dev/null || true

# Supprimer .git pour repartir propre
rm -rf .git

# Réinitialiser
git init
git branch -M main

# Mettre à jour les fichiers avec v5.7.1
echo "v5.7.1" > VERSION

# Mettre à jour README.md avec la bonne version
sed -i 's/v1\.0\.0/v5.7.1/g' README.md
sed -i 's/Version 1\.0\.0/Version 5.7.1/g' README.md

# Mettre à jour CHANGELOG.md
sed -i 's/\[1\.0\.0\]/[5.7.1]/g' CHANGELOG.md
sed -i 's/Version 1\.0\.0/Version 5.7.1/g' CHANGELOG.md

echo "✅ Fichiers mis à jour avec v5.7.1"

# Ajouter tous les fichiers
git add .

# Commit initial avec votre version
git commit -m "feat: version initiale v5.7.1

🚀 StudiosDB - Système de gestion d'écoles de karaté

📊 Version 5.7.1 :
- 5 = Cinquième itération du système
- 7 = Sept modules fonctionnels complets  
- 1 = Première correction d'ajustements

✨ Fonctionnalités principales:
- Architecture multi-tenant sécurisée
- 7 modules complets (Users, Écoles, Cours, Ceintures, etc.)
- Interface d'administration moderne avec Telescope
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

🗄️ Base de données:
- MySQL 8.0.42 (LkmP0km1)
- 28 tables optimisées
- Multi-tenant strict

Version: v5.7.1
Laravel: 12.19.3 LTS
PHP: 8.3.6"

# Créer tag avec votre version
git tag -a "v5.7.1" -m "Version 5.7.1 - StudiosDB Complet

Cinquième version majeure avec 7 modules fonctionnels et ajustements.

📊 Nomenclature v5.7.1:
- 5 = Cinquième itération/version du système
- 7 = Sept modules de gestion complets
- 1 = Première série d'ajustements et corrections

🎯 Highlights v5.7.1:
- Système multi-tenant complet et sécurisé
- 7 modules de gestion entièrement fonctionnels
- Interface d'administration moderne avec dashboard
- Intégration Laravel Telescope pour monitoring
- Conformité réglementaire (Loi 25)
- Architecture Laravel 12.19 professionnelle
- Base de données optimisée (LkmP0km1)

🛠️ Technique:
- Laravel 12.19.3 LTS
- PHP 8.3.6
- MySQL 8.0.42
- Tailwind CSS
- Spatie Permissions

Repository: https://github.com/lalphadb/studiosunisdb"

# Connecter à GitHub
git remote add origin https://github.com/lalphadb/studiosunisdb.git

echo ""
echo "✅ DÉPÔT PRÉPARÉ AVEC v5.7.1 !"
echo ""
echo "🚀 PUSH SUR GITHUB..."

# Push main
git push -u origin main

# Push tag
git push origin v5.7.1

echo ""
echo "✅ VERSION v5.7.1 POUSSÉE SUR GITHUB !"
echo ""
echo "📋 LIENS UTILES:"
echo "• Repository: https://github.com/lalphadb/studiosunisdb"
echo "• Release: https://github.com/lalphadb/studiosunisdb/releases/tag/v5.7.1"
echo "• Commits: https://github.com/lalphadb/studiosunisdb/commits/main"
echo ""
echo "🏷️ Votre nomenclature v5.7.1 :"
echo "• 5 = Cinquième version du système"
echo "• 7 = Sept modules fonctionnels"  
echo "• 1 = Première correction d'ajustements"
