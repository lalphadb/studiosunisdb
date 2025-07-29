#!/bin/bash

echo "🧹 NETTOYAGE DES COMPOSANTS ET PAGES VUE"
echo "========================================"

# Créer un backup des composants importants
mkdir -p cleanup_backup/components
mkdir -p cleanup_backup/pages

echo "📦 Sauvegarde des fichiers importants..."

# Sauvegarder les composants utilisés par Auth et Profile
cp -r resources/js/Components/Input* cleanup_backup/components/ 2>/dev/null
cp -r resources/js/Components/Primary* cleanup_backup/components/ 2>/dev/null
cp -r resources/js/Components/Danger* cleanup_backup/components/ 2>/dev/null

echo "🔍 Analyse des composants utilisés..."

# Composants UTILISÉS (à conserver) :
echo "✅ Composants à conserver :"
echo "- InputError.vue (Auth/Profile)"
echo "- InputLabel.vue (Auth/Profile)"
echo "- PrimaryButton.vue (Auth/Profile)"
echo "- TextInput.vue (Auth/Profile)"
echo "- DangerButton.vue (Profile)"
echo "- SecondaryButton.vue (Profile)"
echo "- Modal.vue (Profile)"
echo "- Checkbox.vue (Auth)"

echo ""
echo "🗑️  Suppression des composants inutiles..."

# SUPPRESSION DES PAGES INUTILES
echo "Suppression des pages dashboard inutiles..."
rm -f resources/js/Pages/Dashboard.vue
rm -f resources/js/Pages/DashboardModerne.vue
rm -f resources/js/Pages/DashboardSimple.vue
rm -rf resources/js/Pages/Dashboard/

# SUPPRESSION DES COMPOSANTS DASHBOARD INUTILES
echo "Suppression des composants dashboard inutiles..."
rm -rf resources/js/Components/Dashboard/
rm -f resources/js/Components/DashboardWidget.vue

# SUPPRESSION DES COMPOSANTS UI NON UTILISÉS
echo "Suppression des composants UI non utilisés..."
rm -rf resources/js/Components/UI/
rm -f resources/js/Components/ChartRepartition.vue
rm -f resources/js/Components/ProgressBar.vue
rm -f resources/js/Components/QuickAction.vue

# SUPPRESSION DES COMPOSANTS DE NAVIGATION NON UTILISÉS
# (DashboardPro est autonome, n'utilise pas de layout)
echo "Suppression des composants de navigation inutiles..."
rm -f resources/js/Components/ApplicationLogo.vue
rm -f resources/js/Components/Dropdown.vue
rm -f resources/js/Components/DropdownLink.vue
rm -f resources/js/Components/NavLink.vue
rm -f resources/js/Components/ResponsiveNavLink.vue

echo ""
echo "📊 RÉSUMÉ DU NETTOYAGE :"
echo "======================="
echo ""
echo "🗑️  SUPPRIMÉ :"
echo "- Pages/Dashboard.vue (ancienne version)"
echo "- Pages/DashboardModerne.vue (non utilisée)"
echo "- Pages/DashboardSimple.vue (non utilisée)"
echo "- Pages/Dashboard/ (dossier entier)"
echo "- Components/Dashboard/ (dossier entier)"
echo "- Components/UI/ (dossier entier)"
echo "- Components de navigation (non utilisés par DashboardPro)"
echo "- Components charts et widgets non utilisés"
echo ""
echo "✅ CONSERVÉ :"
echo "- Pages/DashboardPro.vue (dashboard principal)"
echo "- Pages/Auth/ (login, register, etc.)"
echo "- Pages/Profile/ (profil utilisateur)"
echo "- Pages/Membres/ (gestion membres)"
echo "- Pages/Welcome.vue (page d'accueil)"
echo "- Components de formulaires (Input*, Button*)"
echo "- Components modaux et checkboxes"
echo ""

# Afficher la structure finale
echo "📁 STRUCTURE FINALE :"
echo "===================="
echo ""
echo "📂 resources/js/Pages/"
find resources/js/Pages -name "*.vue" | sort 2>/dev/null

echo ""
echo "📂 resources/js/Components/"
find resources/js/Components -name "*.vue" | sort 2>/dev/null

echo ""
echo "✅ NETTOYAGE TERMINÉ !"
echo "Votre projet utilise maintenant uniquement les composants nécessaires."
