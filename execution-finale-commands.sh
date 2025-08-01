# 🚀 EXÉCUTION FINALE STUDIOSDB V5 PRO - COMMANDES IMMÉDIATES

echo "🎯 === EXÉCUTION STUDIOSDB V5 PRO ==="

# 📁 Navigation vers le projet
cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 🎮 OPTION 1: SCRIPT MAÎTRE (RECOMMANDÉ) - Déploiement complet avec menu
echo "🎮 OPTION 1: SCRIPT MAÎTRE INTERACTIF"
chmod +x master-deployment-script.sh
./master-deployment-script.sh
# Menu interactif avec options:
# 1. Maintenance seule
# 2. README seul  
# 3. GitHub seul
# 4. TOUT (Maintenance + README + GitHub)

echo ""
echo "=" | tr ' ' '=' | head -c 60; echo

# ⚡ OPTION 2: SCRIPTS INDIVIDUELS (Manuel)
echo "⚡ OPTION 2: SCRIPTS INDIVIDUELS"

# Maintenance complète
echo "🛠️ Maintenance complète:"
chmod +x project-maintenance-complete.sh
./project-maintenance-complete.sh

# README mise à jour
echo "📝 README mise à jour:"
chmod +x update-readme-script.sh  
./update-readme-script.sh

# Commit GitHub
echo "🚀 GitHub commit:"
chmod +x github-commit-script.sh
./github-commit-script.sh

echo ""
echo "=" | tr ' ' '=' | head -c 60; echo

# 🎯 OPTION 3: COMMANDES DIRECTES (Ultra-rapide)
echo "🎯 OPTION 3: COMMANDES DIRECTES"

# Correction dashboard immédiate
echo "⚡ Dashboard correction:"
php artisan cache:clear
php artisan optimize
curl -w "Temps: %{time_total}s\n" -s -o /dev/null http://localhost:8000/dashboard

# Git commit rapide
echo "📤 Git commit rapide:"
git add .
git commit -m "feat: StudiosDB v5.1.2 - Optimisation complète"
git push origin main

echo ""
echo "✅ STUDIOSDB V5 PRO DÉPLOYÉ !"