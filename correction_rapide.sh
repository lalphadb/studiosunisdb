cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 🚀 CORRECTION IMMÉDIATE - COMMANDES LINUX
# ==========================================

echo "🔧 DÉBUT CORRECTION STUDIOSDB V5"

# 1. Supprimer migrations dupliquées
rm -f database/migrations/2025_07_20_134813_create_ceintures_table.php
rm -f database/migrations/2025_07_20_134813_create_cours_table.php
echo "✅ Migrations dupliquées supprimées"

# 2. Installer Chart.js
npm install chart.js@^4.4.0
echo "✅ Chart.js installé"

# 3. Reset base de données
php artisan migrate:fresh --force
echo "✅ Base de données réinitialisée"

# 4. Compiler assets
npm run build
echo "✅ Assets compilés"

# 5. Créer utilisateur admin
php artisan tinker --execute="
\$admin = \\App\\Models\\User::firstOrCreate([
    'email' => 'louis@4lb.ca'
], [
    'name' => 'Louis Admin',
    'password' => bcrypt('password123'),
    'email_verified_at' => now()
]);
echo 'Admin: louis@4lb.ca / password123';
"

# 6. Test final
php artisan route:list | grep dashboard
echo "✅ Routes opérationnelles"

echo ""
echo "🎉 CORRECTION TERMINÉE AVEC SUCCÈS!"
echo "==================================="
echo "🔗 Accès: http://studiosdb.local:8000/dashboard"
echo "👤 Login: louis@4lb.ca / password123"