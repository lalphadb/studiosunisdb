#!/bin/bash

echo "🔭 === VÉRIFICATION CONFIGURATION TELESCOPE ==="
echo ""

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

echo "📋 Configuration Telescope:"
php artisan tinker --execute="
echo 'Enabled: ' . (config('telescope.enabled') ? '✅ YES' : '❌ NO');
echo 'Path: ' . config('telescope.path');
echo 'Driver: ' . config('telescope.driver');
echo 'Domain: ' . (config('telescope.domain') ?: 'default');
"

echo ""
echo "📊 Base de données:"
php artisan tinker --execute="
echo 'Telescope entries: ' . \Illuminate\Support\Facades\DB::table('telescope_entries')->count();
echo 'Recent entries (last hour): ' . \Illuminate\Support\Facades\DB::table('telescope_entries')->where('created_at', '>=', now()->subHour())->count();
"

echo ""
echo "🎛️ Watchers actifs:"
php artisan tinker --execute="
\$watchers = config('telescope.watchers');
\$active = array_filter(\$watchers, function(\$value) { 
    return is_bool(\$value) ? \$value : (\$value['enabled'] ?? true); 
});
echo 'Total watchers: ' . count(\$watchers);
echo 'Active watchers: ' . count(\$active);
"

echo ""
echo "🌐 Test accès:"
response=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/telescope)
if [ "$response" = "200" ]; then
    echo "✅ Telescope accessible (HTTP $response)"
else
    echo "❌ Telescope non accessible (HTTP $response)"
fi

echo ""
echo "🔍 Variables d'environnement Telescope:"
grep "TELESCOPE_" /home/studiosdb/studiosunisdb/studiosdb_v5_pro/.env | head -5

echo ""
echo "✅ Vérification terminée!"
echo "🔗 Accès: http://localhost:8000/telescope"
