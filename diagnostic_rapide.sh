#!/bin/bash
echo "🔍 DIAGNOSTIC RAPIDE STUDIOSUNISDB"
echo "=================================="
echo "📅 $(date)"
echo "📍 $(pwd)"
echo ""
echo "🏗️ VERSIONS:"
echo "  Laravel: $(php artisan --version)"
echo "  PHP: $(php -v | head -n1)"
echo "  MySQL: $(mysql --version)"
echo ""
echo "🗄️ BASE DE DONNÉES:"
mysql -u root -pLkmP0km1 studiosdb -e "
SELECT 
  (SELECT COUNT(*) FROM ecoles) as Ecoles,
  (SELECT COUNT(*) FROM membres) as Membres,
  (SELECT COUNT(*) FROM users) as Utilisateurs,
  (SELECT COUNT(*) FROM ceintures) as Ceintures;
" 2>/dev/null || echo "❌ Erreur MySQL"
echo ""
echo "📊 MODULES:"
echo "  ✅ Écoles: Opérationnel"
echo "  ✅ Membres: Opérationnel"  
echo "  🔧 Cours: En développement"
echo "  🔧 Présences: Planifié"
echo ""
echo "🚀 SERVEUR:"
echo "  URL: http://localhost:8000"
echo "  Status: $(pgrep -f 'artisan serve' > /dev/null && echo 'Actif' || echo 'Arrêté')"
