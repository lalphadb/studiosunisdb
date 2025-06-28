#!/bin/bash

echo "🔄 CONVERSION DE TOUTES LES VUES ADMIN vers x-module-header"
echo "==========================================================="

# Liste des modules avec leurs configurations
declare -A modules=(
    ["seminaires"]="seminaire|Séminaires|pink-500|purple-600|🎯"
    ["ceintures"]="ceinture|Ceintures|orange-500|red-600|🥋" 
    ["paiements"]="paiement|Paiements|yellow-500|orange-600|💰"
    ["presences"]="presence|Présences|teal-500|green-600|✅"
    ["logs"]="dashboard|Logs|blue-500|cyan-600|📊"
)

# Fonction pour convertir une vue
convert_view() {
    local file="$1"
    local module="$2"
    local title="$3"
    local subtitle="$4"
    
    if [ -f "$file" ]; then
        echo "📝 Conversion: $file"
        
        # Backup
        cp "$file" "${file}.backup"
        
        # Ajouter x-module-header si pas déjà présent
        if ! grep -q "x-module-header" "$file"; then
            # Chercher la ligne après @section('content')
            sed -i "/^@section('content')/a\\
<div class=\"space-y-6\">\\
    <!-- Header avec x-module-header -->\\
    <x-module-header \\
        module=\"$module\"\\
        title=\"$title\"\\
        subtitle=\"$subtitle\"\\
        :create-route=\"route('admin.$module.create')\"\\
        create-text=\"Nouveau\"\\
        create-permission=\"create,App\\\\Models\\\\$(echo $module | sed 's/^./\U&/')\"\\
    />\\
" "$file"
        fi
    fi
}

# Conversion selon les modules
for module_key in "${!modules[@]}"; do
    IFS='|' read -r module title primary secondary icon <<< "${modules[$module_key]}"
    
    echo "🔧 Module: $module_key ($module)"
    
    # Index
    if [ -f "resources/views/admin/$module_key/index.blade.php" ]; then
        convert_view "resources/views/admin/$module_key/index.blade.php" "$module" "Gestion des $title" "Administration des $title du système"
    fi
    
    # Show  
    if [ -f "resources/views/admin/$module_key/show.blade.php" ]; then
        convert_view "resources/views/admin/$module_key/show.blade.php" "$module" "Détails $title" "Informations détaillées"
    fi
done

echo "✅ Conversion terminée"
echo "📁 Backups créés avec extension .backup"
