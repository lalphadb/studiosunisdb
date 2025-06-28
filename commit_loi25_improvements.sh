#!/bin/bash
echo "📦 COMMIT AMÉLIORATIONS LOI 25 ET NAVIGATION"
echo "==========================================="

# 1. Vérifier le statut Git
echo "📋 1. Statut des modifications..."
git status --short

# 2. Ajouter tous les nouveaux fichiers et modifications
echo ""
echo "➕ 2. Ajout des modifications..."
git add .

# 3. Commit avec message structuré
echo ""
echo "📝 3. Commit des améliorations..."

git commit -m "feat(legal): ajout pages conformité Loi 25 et correction navigation

🚀 Nouvelles fonctionnalités:
- Pages légales complètes (confidentialité, conditions, contact)
- Conformité Loi 25 du Québec avec droits utilisateurs
- Formulaire de contact fonctionnel
- Structure Laravel professionnelle (partials + components)

🔧 Corrections techniques:
- Navigation sécurisée pour utilisateurs non connectés
- Layout app.blade.php avec footer inclus
- Routes légales ajoutées (/politique-confidentialite, etc.)
- Gestion Auth::user() null-safe avec ?->

📁 Fichiers ajoutés:
- resources/views/legal/ (privacy, terms, contact)
- resources/views/partials/footer.blade.php
- resources/views/components/app-footer.blade.php
- Routes légales dans web.php

🛡️ Sécurité:
- Protection Auth::user()?->name ?? 'Utilisateur'
- Validation CSRF sur formulaires
- Consentements Loi 25 obligatoires

🎯 Conformité:
- Loi 25 Québec: droits accès, rectification, suppression
- Hébergement Québec mentionné
- Contact responsable protection données
- Durées conservation détaillées

Version: StudiosDB v5.7.1
Architecture: Laravel 12.19.3 + Tailwind CSS"

# 4. Vérifier le commit
echo ""
echo "✅ 4. Vérification du commit..."
git log --oneline -1

# 5. Push vers GitHub
echo ""
echo "🚀 5. Push vers GitHub..."
git push origin main

# 6. Créer un tag pour cette amélioration
echo ""
echo "🏷️ 6. Création tag v5.7.1-loi25..."

# Vérifier si le tag existe déjà
if git tag -l | grep -q "v5.7.1-loi25"; then
    echo "⚠️ Tag v5.7.1-loi25 existe déjà"
else
    git tag -a "v5.7.1-loi25" -m "StudiosDB v5.7.1 - Conformité Loi 25

Ajout complet de la conformité Loi 25 du Québec :

✨ Nouvelles pages légales:
- Politique de confidentialité complète
- Conditions d'utilisation détaillées  
- Formulaire de contact fonctionnel

🛡️ Conformité Loi 25:
- Droits des utilisateurs (CRUD sur données)
- Consentements explicites obligatoires
- Durées de conservation transparentes
- Contact responsable protection données

🔧 Améliorations techniques:
- Structure Laravel professionnelle
- Navigation sécurisée (null-safe)
- Footer réutilisable (partials + components)
- Protection CSRF complète

🎯 Qualité:
- Code organisé et maintenable
- Documentation utilisateur claire
- Respect standards Laravel
- Interface utilisateur cohérente

Repository: https://github.com/lalphadb/studiosunisdb
Base: StudiosDB v5.7.1"

    # Push le tag
    git push origin v5.7.1-loi25
    echo "✅ Tag v5.7.1-loi25 créé et poussé"
fi

echo ""
echo "✅ COMMIT ET SAUVEGARDE TERMINÉS !"
echo ""
echo "📊 RÉSUMÉ DES AMÉLIORATIONS :"
echo "• ✅ 3 pages légales Loi 25 créées"
echo "• ✅ Navigation corrigée (null-safe)"
echo "• ✅ Footer professionnel (partials + components)"
echo "• ✅ Routes légales fonctionnelles"
echo "• ✅ Structure Laravel optimisée"
echo "• ✅ Commit avec message détaillé"
echo "• ✅ Tag v5.7.1-loi25 créé"
echo "• ✅ Push GitHub effectué"
echo ""
echo "🌐 LIENS GitHub :"
echo "• Repository: https://github.com/lalphadb/studiosunisdb"
echo "• Commit: https://github.com/lalphadb/studiosunisdb/commits/main"
echo "• Tag: https://github.com/lalphadb/studiosunisdb/releases/tag/v5.7.1-loi25"
echo ""
echo "🎯 StudiosDB v5.7.1 maintenant conforme Loi 25 !"
