#!/bin/bash

echo "🔄 Conversion x-module-header pour toutes les vues admin..."

# Vues à convertir avec leur configuration
declare -A views=(
    # Séminaires
    ["seminaires/index"]="seminaire|Gestion des Séminaires|Administration des séminaires et événements"
    ["seminaires/show"]="seminaire|Détails du Séminaire|Informations complètes du séminaire"
    ["seminaires/inscriptions"]="seminaire|Inscriptions Séminaire|Gestion des participants"
    
    # Ceintures  
    ["ceintures/index"]="ceinture|Gestion des Ceintures|Administration des grades et progressions"
    ["ceintures/show"]="ceinture|Détails de la Ceinture|Informations sur la ceinture"
    
    # Paiements
    ["paiements/index"]="paiement|Gestion des Paiements|Suivi financier et transactions"
    ["paiements/show"]="paiement|Détails du Paiement|Transaction et informations financières"
    
    # Présences
    ["presences/index"]="presence|Gestion des Présences|Suivi de la présence aux cours"
    ["presences/show"]="presence|Détails de Présence|Informations sur la présence"
    ["presences/prise-presence"]="presence|Prise de Présence|Enregistrer les présences du cours"
    
    # Logs
    ["logs/index"]="dashboard|Logs Système|Historique des actions du système"
)

# Fonction pour convertir une vue
convert_view() {
    local view_path="$1"
    local config="$2"
    
    IFS='|' read -r module title subtitle <<< "$config"
    
    local file="resources/views/admin/$view_path.blade.php"
    
    if [ -f "$file" ]; then
        echo "📝 Conversion: $view_path"
        
        # Backup
        cp "$file" "${file}.backup"
        
        # Vérifier si x-module-header existe déjà
        if ! grep -q "x-module-header" "$file"; then
            # Ajouter x-module-header après @section('content')
            sed -i "/^@section('content')/a\\
<div class=\"space-y-6\">\\
    <!-- Header avec x-module-header (OBLIGATOIRE selon prompt XML) -->\\
    <x-module-header \\
        module=\"$module\"\\
        title=\"$title\"\\
        subtitle=\"$subtitle\"\\
        :create-route=\"null\"\\
        create-text=\"\"\\
        create-permission=\"null\"\\
    />\\
" "$file"
            
            echo "✅ $view_path converti"
        else
            echo "ℹ️ $view_path déjà converti"
        fi
    else
        echo "⚠️ $view_path non trouvé"
    fi
}

# Convertir toutes les vues configurées
for view in "${!views[@]}"; do
    convert_view "$view" "${views[$view]}"
done

echo ""
echo "📊 Résultat:"
total_admin=$(find resources/views/admin -name "*.blade.php" | wc -l)
with_header=$(find resources/views/admin -name "*.blade.php" -exec grep -l "x-module-header" {} \; | wc -l)
echo "x-module-header: $with_header/$total_admin vues admin"

