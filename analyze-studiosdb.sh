#!/bin/bash
PROJECT_PATH="/home/studiosdb/studiosunisdb/studiosdb-v2"
REPORT_FILE="$PROJECT_PATH/project-analysis-report.xml"

# Vérifier répertoire
if [ ! -f "$PROJECT_PATH/artisan" ]; then
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" > $REPORT_FILE
    echo "<AnalysisReport project=\"StudiosDB Enterprise\" version=\"4.1.10.2\" date=\"$(date +%F)\">" >> $REPORT_FILE
    echo "<Error message=\"Artisan not found in $PROJECT_PATH\" />" >> $REPORT_FILE
    echo "</AnalysisReport>" >> $REPORT_FILE
    cat $REPORT_FILE
    exit 1
fi

# Initialiser le rapport XML
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" > $REPORT_FILE
echo "<AnalysisReport project=\"StudiosDB Enterprise\" version=\"4.1.10.2\" date=\"$(date +%F)\">" >> $REPORT_FILE

# Informations générales
echo "<Project>" >> $REPORT_FILE
echo "<Framework>Laravel 12.20</Framework>" >> $REPORT_FILE
echo "<PHPVersion>8.3+</PHPVersion>" >> $REPORT_FILE
echo "<CSS>Tailwind CSS</CSS>" >> $REPORT_FILE
echo "<JavaScript>ES6 Modules</JavaScript>" >> $REPORT_FILE
echo "</Project>" >> $REPORT_FILE

# Base de données
TABLES=$(php artisan tinker --execute="echo json_encode(array_column(DB::select('SHOW TABLES'), 'Tables_in_studiosdb'));" 2>/dev/null | jq -r '.[]')
echo "<Database>" >> $REPORT_FILE
echo "<Type>MySQL</Type>" >> $REPORT_FILE
echo "<Tables count=\"$(echo \"$TABLES\" | wc -l)\">" >> $REPORT_FILE
for table in $TABLES; do
    echo "<Table name=\"$table\" />" >> $REPORT_FILE
done
echo "</Tables>" >> $REPORT_FILE
echo "<MultiTenant field=\"ecole_id\" />" >> $REPORT_FILE
echo "</Database>" >> $REPORT_FILE

# Modèles
MODELS=$(ls -1 $PROJECT_PATH/app/Models | grep ".php$" | sed 's/\.php//')
echo "<Models count=\"$(echo \"$MODELS\" | wc -l)\">" >> $REPORT_FILE
for model in $MODELS; do
    echo "<Model name=\"$model\" />" >> $REPORT_FILE
done
echo "</Models>" >> $REPORT_FILE

# Contrôleurs
CONTROLLERS=$(ls -1 $PROJECT_PATH/app/Http/Controllers/Admin | grep ".php$" | sed 's/\.php//')
echo "<Controllers count=\"$(echo \"$CONTROLLERS\" | wc -l)\">" >> $REPORT_FILE
for controller in $CONTROLLERS; do
    echo "<Controller name=\"$controller\" />" >> $REPORT_FILE
done
echo "</Controllers>" >> $REPORT_FILE

# Composants Blade
COMPONENTS=$(ls -1 $PROJECT_PATH/resources/views/components | grep ".blade.php$" | sed 's/\.blade\.php//')
echo "<BladeComponents count=\"$(echo \"$COMPONENTS\" | wc -l)\">" >> $REPORT_FILE
for component in $COMPONENTS; do
    echo "<Component name=\"$component\" />" >> $REPORT_FILE
done
echo "</BladeComponents>" >> $REPORT_FILE

# Services
SERVICES=$(ls -1 $PROJECT_PATH/app/Services | grep ".php$" | sed 's/\.php//')
echo "<Services count=\"$(echo \"$SERVICES\" | wc -l)\">" >> $REPORT_FILE
for service in $SERVICES; do
    echo "<Service name=\"$service\" />" >> $REPORT_FILE
done
echo "</Services>" >> $REPORT_FILE

# Livewire
LIVEWIRE_ACTIONS=$(ls -1 $PROJECT_PATH/app/Livewire/Actions 2>/dev/null | grep ".php$" | sed 's/\.php//')
LIVEWIRE_FORMS=$(ls -1 $PROJECT_PATH/app/Livewire/Forms 2>/dev/null | grep ".php$" | sed 's/\.php//')
echo "<LivewireComponents>" >> $REPORT_FILE
echo "<Actions count=\"$(echo \"$LIVEWIRE_ACTIONS\" | wc -l)\">" >> $REPORT_FILE
for action in $LIVEWIRE_ACTIONS; do
    echo "<Action name=\"$action\" />" >> $REPORT_FILE
done
echo "</Actions>" >> $REPORT_FILE
echo "<Forms count=\"$(echo \"$LIVEWIRE_FORMS\" | wc -l)\">" >> $REPORT_FILE
for form in $LIVEWIRE_FORMS; do
    echo "<Form name=\"$form\" />" >> $REPORT_FILE
done
echo "</Forms>" >> $REPORT_FILE
echo "</LivewireComponents>" >> $REPORT_FILE

# Authentification
echo "<Authentication>" >> $REPORT_FILE
echo "<System>Laravel Fortify</System>" >> $REPORT_FILE
echo "<Middleware>auth, verified, 2fa</Middleware>" >> $REPORT_FILE
echo "<Permissions>Spatie Permission 6.20.0</Permissions>" >> $REPORT_FILE
echo "</Authentication>" >> $REPORT_FILE

# Dashboard
echo "<Dashboard>" >> $REPORT_FILE
echo "<Features>" >> $REPORT_FILE
echo "<Feature>Statistiques en temps réel</Feature>" >> $REPORT_FILE
echo "<Feature>Graphiques Canvas natifs</Feature>" >> $REPORT_FILE
echo "<Feature>Animations et compteurs</Feature>" >> $REPORT_FILE
echo "<Feature>Monitoring système</Feature>" >> $REPORT_FILE
echo "</Features>" >> $REPORT_FILE
echo "</Dashboard>" >> $REPORT_FILE

# Sécurité
echo "<Security>" >> $REPORT_FILE
echo "<Features>" >> $REPORT_FILE
echo "<Feature>2FA via Fortify</Feature>" >> $REPORT_FILE
echo "<Feature>CSRF et XSS protection</Feature>" >> $REPORT_FILE
echo "<Feature>Activity Log</Feature>" >> $REPORT_FILE
echo "<Feature>Telescope Monitoring</Feature>" >> $REPORT_FILE
echo "</Features>" >> $REPORT_FILE
echo "</Security>" >> $REPORT_FILE

# Fermer le rapport
echo "</AnalysisReport>" >> $REPORT_FILE

# Afficher le rapport
cat $REPORT_FILE

chmod +x $PROJECT_PATH/analyze-studiosdb.sh
