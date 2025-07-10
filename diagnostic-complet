#!/bin/bash

# ===================================================================
# ANALYSEUR COMPLET STUDIOSDB - DIAGNOSTIC ULTRA-DÉTAILLÉ
# ===================================================================
# Usage: bash analyze-studiosdb-complete.sh > diagnostic-complet.xml
# ===================================================================

echo '<?xml version="1.0" encoding="UTF-8"?>'
echo '<studiosdb_diagnostic_complet>'
echo "  <timestamp>$(date -u +"%Y-%m-%dT%H:%M:%SZ")</timestamp>"
echo "  <project_root>$(pwd)</project_root>"

# ===================================================================
# 1. VÉRIFICATION DES FICHIERS CRITIQUES
# ===================================================================
echo '  <fichiers_critiques>'

# BaseAdminController - LE PLUS IMPORTANT
if [ -f "app/Http/Controllers/Admin/BaseAdminController.php" ]; then
    echo '    <BaseAdminController exists="true">'
    echo "      <path>app/Http/Controllers/Admin/BaseAdminController.php</path>"
    echo "      <size>$(stat -c%s "app/Http/Controllers/Admin/BaseAdminController.php" 2>/dev/null || echo "0") bytes</size>"
    echo "      <lines>$(wc -l < "app/Http/Controllers/Admin/BaseAdminController.php" 2>/dev/null || echo "0")</lines>"
    
    # Vérifier le namespace
    namespace=$(grep -m1 "^namespace" "app/Http/Controllers/Admin/BaseAdminController.php" 2>/dev/null || echo "NOT_FOUND")
    echo "      <namespace>$namespace</namespace>"
    
    # Vérifier la classe
    class_def=$(grep -m1 "^class BaseAdminController" "app/Http/Controllers/Admin/BaseAdminController.php" 2>/dev/null || echo "NOT_FOUND")
    echo "      <class_definition>$class_def</class_definition>"
    echo '    </BaseAdminController>'
else
    echo '    <BaseAdminController exists="false" />'
fi

# Vérifier tous les controllers Admin
echo '    <controllers_admin>'
for controller in app/Http/Controllers/Admin/*.php; do
    if [ -f "$controller" ]; then
        filename=$(basename "$controller")
        echo "      <controller name=\"$filename\">"
        
        # Vérifier si hérite de BaseAdminController
        extends=$(grep -m1 "extends BaseAdminController" "$controller" 2>/dev/null)
        if [ ! -z "$extends" ]; then
            echo "        <extends_base>true</extends_base>"
        else
            echo "        <extends_base>false</extends_base>"
        fi
        
        echo "      </controller>"
    fi
done
echo '    </controllers_admin>'

# Vérifier les components critiques
echo '    <components_blade>'
components=(
    "components/admin/navigation.blade.php"
    "components/admin/sidebar.blade.php"
    "components/admin/flash-messages.blade.php"
    "components/module-header.blade.php"
    "components/admin-table.blade.php"
    "components/admin-icon.blade.php"
    "components/primary-button.blade.php"
    "components/secondary-button.blade.php"
    "components/danger-button.blade.php"
)

for comp in "${components[@]}"; do
    if [ -f "resources/views/$comp" ]; then
        echo "      <component name=\"$comp\" exists=\"true\" />"
    else
        echo "      <component name=\"$comp\" exists=\"false\" />"
    fi
done
echo '    </components_blade>'

echo '  </fichiers_critiques>'

# ===================================================================
# 2. CONFIGURATION PHP & LARAVEL
# ===================================================================
echo '  <configuration>'
echo '    <php>'
echo "      <version>$(php -v | head -n1)</version>"
echo "      <memory_limit>$(php -r 'echo ini_get("memory_limit");')</memory_limit>"
echo "      <max_execution_time>$(php -r 'echo ini_get("max_execution_time");')</max_execution_time>"
echo '    </php>'

echo '    <laravel>'
if [ -f "artisan" ]; then
    echo "      <version>$(php artisan --version 2>/dev/null || echo "ERROR")</version>"
fi
echo '    </laravel>'

echo '    <composer>'
# Vérifier les packages critiques
packages=(
    "laravel/framework"
    "spatie/laravel-permission"
    "laravel/fortify"
    "laravel/telescope"
    "maatwebsite/excel"
    "barryvdh/laravel-dompdf"
)

for package in "${packages[@]}"; do
    version=$(composer show "$package" 2>/dev/null | grep "versions" | awk '{print $3}' || echo "NOT_INSTALLED")
    echo "      <package name=\"$package\" version=\"$version\" />"
done
echo '    </composer>'

echo '    <env_variables>'
if [ -f ".env" ]; then
    echo "      <APP_ENV>$(grep "^APP_ENV=" .env | cut -d'=' -f2)</APP_ENV>"
    echo "      <APP_DEBUG>$(grep "^APP_DEBUG=" .env | cut -d'=' -f2)</APP_DEBUG>"
    echo "      <APP_URL>$(grep "^APP_URL=" .env | cut -d'=' -f2)</APP_URL>"
    echo "      <DB_CONNECTION>$(grep "^DB_CONNECTION=" .env | cut -d'=' -f2)</DB_CONNECTION>"
    echo "      <QUEUE_CONNECTION>$(grep "^QUEUE_CONNECTION=" .env | cut -d'=' -f2)</QUEUE_CONNECTION>"
fi
echo '    </env_variables>'
echo '  </configuration>'

# ===================================================================
# 3. BASE DE DONNÉES
# ===================================================================
echo '  <database>'

# Vérifier les migrations
echo '    <migrations>'
if [ -d "database/migrations" ]; then
    migration_count=$(ls -1 database/migrations/*.php 2>/dev/null | wc -l)
    echo "      <total>$migration_count</total>"
    
    # Vérifier les doublons
    echo '      <doublons>'
    for file in database/migrations/*add_proprietaire_to_ecoles_table*.php; do
        if [ -f "$file" ]; then
            echo "        <migration>$(basename "$file")</migration>"
        fi
    done
    echo '      </doublons>'
    
    # Vérifier le statut des migrations
    echo '      <status>'
    php artisan migrate:status --no-ansi 2>/dev/null | tail -n +4 | while read line; do
        if [ ! -z "$line" ]; then
            echo "        <migration>$line</migration>"
        fi
    done
    echo '      </status>'
fi
echo '    </migrations>'

# Vérifier les tables
echo '    <tables>'
if command -v mysql &> /dev/null; then
    db_name=$(grep "^DB_DATABASE=" .env | cut -d'=' -f2)
    db_user=$(grep "^DB_USERNAME=" .env | cut -d'=' -f2)
    db_pass=$(grep "^DB_PASSWORD=" .env | cut -d'=' -f2)
    
    if [ ! -z "$db_name" ]; then
        mysql -u"$db_user" -p"$db_pass" "$db_name" -e "SHOW TABLES;" 2>/dev/null | tail -n +2 | while read table; do
            echo "        <table name=\"$table\" />"
        done
    fi
fi
echo '    </tables>'
echo '  </database>'

# ===================================================================
# 4. VÉRIFICATION DES VUES PAR MODULE
# ===================================================================
echo '  <modules_views>'

modules=(
    "dashboard:index,admin-ecole,superadmin"
    "users:index,create,edit,show"
    "ecoles:index,create,edit,show"
    "cours:index,create,edit,show,clone"
    "ceintures:index,create,edit,show,create-masse"
    "presences:index,create,edit,show,prise-presence"
    "paiements:index,create,edit,show,validation-rapide"
    "seminaires:index,create,edit,show,inscriptions"
    "sessions:index,create,edit,show"
)

for module_info in "${modules[@]}"; do
    module_name=$(echo $module_info | cut -d: -f1)
    views=$(echo $module_info | cut -d: -f2)
    
    echo "    <module name=\"$module_name\">"
    
    IFS=',' read -ra view_array <<< "$views"
    for view in "${view_array[@]}"; do
        view_path="resources/views/admin/$module_name/$view.blade.php"
        if [ -f "$view_path" ]; then
            echo "      <view name=\"$view\" exists=\"true\" />"
        else
            echo "      <view name=\"$view\" exists=\"false\" />"
        fi
    done
    
    echo "    </module>"
done

echo '  </modules_views>'

# ===================================================================
# 5. ROUTES
# ===================================================================
echo '  <routes>'

# Vérifier les fichiers de routes
route_files=("web.php" "admin.php" "api.php" "auth.php")
for route_file in "${route_files[@]}"; do
    if [ -f "routes/$route_file" ]; then
        echo "    <route_file name=\"$route_file\" exists=\"true\">"
        
        # Compter les routes
        route_count=$(grep -c "Route::" "routes/$route_file" 2>/dev/null || echo "0")
        echo "      <route_count>$route_count</route_count>"
        
        # Pour admin.php, vérifier les routes spécifiques
        if [ "$route_file" == "admin.php" ]; then
            echo '      <admin_routes>'
            grep "Route::" "routes/$route_file" 2>/dev/null | grep -o "Route::[a-z]*('[^']*'" | while read route; do
                echo "        <route>$route</route>"
            done
            echo '      </admin_routes>'
        fi
        
        echo "    </route_file>"
    else
        echo "    <route_file name=\"$route_file\" exists=\"false\" />"
    fi
done

echo '  </routes>'

# ===================================================================
# 6. PERMISSIONS & RÔLES
# ===================================================================
echo '  <permissions_roles>'

# Vérifier si Spatie est configuré
if [ -f "config/permission.php" ]; then
    echo '    <spatie_configured>true</spatie_configured>'
    
    # Lister les rôles depuis la DB
    echo '    <roles>'
    php artisan tinker --execute="use Spatie\Permission\Models\Role; Role::all()->pluck('name')->each(function(\$role) { echo \"      <role>\$role</role>\n\"; });" 2>/dev/null || echo "      <error>Cannot fetch roles</error>"
    echo '    </roles>'
else
    echo '    <spatie_configured>false</spatie_configured>'
fi

echo '  </permissions_roles>'

# ===================================================================
# 7. ASSETS & COMPILATION
# ===================================================================
echo '  <assets>'

# Vérifier les fichiers de configuration
config_files=("vite.config.js" "tailwind.config.js" "postcss.config.js")
for config in "${config_files[@]}"; do
    if [ -f "$config" ]; then
        echo "    <config name=\"$config\" exists=\"true\" />"
    else
        echo "    <config name=\"$config\" exists=\"false\" />"
    fi
done

# Vérifier si les assets sont compilés
if [ -d "public/build" ]; then
    echo '    <compiled>true</compiled>'
    echo "    <build_files>$(ls -1 public/build/ 2>/dev/null | wc -l)</build_files>"
else
    echo '    <compiled>false</compiled>'
fi

echo '  </assets>'

# ===================================================================
# 8. LOGS & ERREURS
# ===================================================================
echo '  <logs>'

# Dernières erreurs Laravel
if [ -f "storage/logs/laravel.log" ]; then
    echo '    <recent_errors>'
    tail -n 20 storage/logs/laravel.log | grep -E "ERROR|CRITICAL|ALERT|EMERGENCY" | tail -n 5 | while read line; do
        echo "      <error><![CDATA[$line]]></error>"
    done
    echo '    </recent_errors>'
fi

echo '  </logs>'

# ===================================================================
# 9. TESTS FONCTIONNELS
# ===================================================================
echo '  <functional_tests>'

# Test de connexion à la DB
echo '    <database_connection>'
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'SUCCESS'; } catch (\Exception \$e) { echo 'FAILED: ' . \$e->getMessage(); }" 2>/dev/null
echo '    </database_connection>'

# Test d'authentification
echo '    <auth_test>'
php artisan tinker --execute="use App\Models\User; \$user = User::where('email', 'lalpha@4lb.ca')->first(); echo \$user ? 'USER_EXISTS' : 'USER_NOT_FOUND';" 2>/dev/null
echo '    </auth_test>'

echo '  </functional_tests>'

# ===================================================================
# 10. RECOMMANDATIONS
# ===================================================================
echo '  <recommandations>'

# Vérifier si BaseAdminController existe
if [ ! -f "app/Http/Controllers/Admin/BaseAdminController.php" ]; then
    echo '    <critical>BaseAdminController MANQUANT - Créer immédiatement</critical>'
fi

# Vérifier les migrations en double
if ls database/migrations/*add_proprietaire_to_ecoles_table*.php 1> /dev/null 2>&1; then
    echo '    <warning>Migrations en double détectées - Nettoyer</warning>'
fi

# Vérifier la compilation des assets
if [ ! -d "public/build" ]; then
    echo '    <info>Assets non compilés - Exécuter: npm run build</info>'
fi

echo '  </recommandations>'

echo '</studiosdb_diagnostic_complet>'

# ===================================================================
# RAPPORT RÉSUMÉ
# ===================================================================
echo ""
echo "# RÉSUMÉ DU DIAGNOSTIC"
echo "====================="
echo ""

# Vérifier BaseAdminController
if [ -f "app/Http/Controllers/Admin/BaseAdminController.php" ]; then
    echo "✅ BaseAdminController: PRÉSENT"
else
    echo "❌ BaseAdminController: MANQUANT (CRITIQUE!)"
fi

# Compter les controllers
controller_count=$(ls -1 app/Http/Controllers/Admin/*.php 2>/dev/null | wc -l)
echo "📁 Controllers Admin: $controller_count fichiers"

# Compter les vues
view_count=$(find resources/views/admin -name "*.blade.php" 2>/dev/null | wc -l)
echo "📄 Vues Admin: $view_count fichiers"

# Compter les components
component_count=$(find resources/views/components -name "*.blade.php" 2>/dev/null | wc -l)
echo "🧩 Components: $component_count fichiers"

echo ""
echo "Diagnostic complet généré dans: diagnostic-complet.xml"
