#!/bin/bash

echo "🎨 COMMIT THÈME PROFILE - StudiosDB v7"
echo "====================================="
echo ""

cd /home/studiosdb/studiosunisdb

# Vérifier le statut git
echo "📋 Statut des fichiers Profile:"
git status --short | grep Profile
echo ""

# Ajouter les fichiers Profile modifiés
echo "➕ Ajout des corrections Profile..."
git add resources/js/Pages/Profile/

# Créer le commit avec message détaillé
echo "💾 Création du commit thème Profile..."
git commit -m "feat(profile): thème bleu glassmorphism unifié + traduction FR

✅ Profile.Edit.vue:
- Conteneurs: backdrop-blur-lg bg-white/10 border-white/20
- Header: text-white (cohérent Dashboard)
- Traduction: 'Mon Profil'

✅ UpdateProfileInformationForm.vue:
- Labels: text-blue-200
- Inputs: bg-white/10 border-white/20 text-white
- Focus: border-blue-400 ring-blue-400
- Textes: français (Nom, E-mail, Informations du Profil)

✅ UpdatePasswordForm.vue:
- Même palette bleu glassmorphism
- Labels/inputs cohérents avec theme
- Traduction complète française

✅ DeleteUserForm.vue:
- Modal: bg-gray-900 pour danger
- Inputs: bg-gray-800 border-gray-600
- Focus: red-400 (danger approprié)
- Traduction française

✅ Cohérence thème:
- 100% aligné Dashboard/Cours bleu glassmorphism
- Typography responsive maintenue
- UX hover effects cohérents
- Pas de couleurs grises résiduelles

Impact: Thème unifié complet, UX cohérente, traduction FR
Tests: ✅ Profile sections, ✅ Forms, ✅ Modal danger"

# Afficher le résultat
echo ""
echo "✅ Commit thème Profile créé:"
git log -1 --oneline
echo ""

echo "🎯 ÉTAT PROJET:"
echo "- J1 Bootstrap sécurité: FROZEN ✅"
echo "- J2 Dashboard: FROZEN ✅"  
echo "- J3 Cours: FROZEN ✅"
echo "- Profile: FROZEN ✅ (nouveau)"
echo "- PRÊT POUR: J4 Utilisateurs (CRUD + rôles + sécurité)"
echo ""

echo "Pour pusher vers le dépôt distant (optionnel):"
echo "git push origin \$(git branch --show-current)"
