#!/bin/bash

echo "🔄 Conversion massive des vues admin restantes..."

# Créer des x-module-header pour toutes les vues admin qui n'en ont pas
find resources/views/admin -name "*.blade.php" -exec grep -L "x-module-header" {} \; | while read file; do
    
    # Extraire le chemin relatif
    relative_path="${file#resources/views/admin/}"
    view_name="${relative_path%.blade.php}"
    
    # Extraire module et action
    module_dir=$(dirname "$view_name")
    action=$(basename "$view_name")
    
    # Configuration par module
    case $module_dir in
        "cours")
            module="cours"
            icon="📚"
            primary="purple-500"
            ;;
        "users")
            module="user"
            icon="👤"
            primary="blue-500"
            ;;
        "ecoles")
            module="ecole"
            icon="🏫"
            primary="green-500"
            ;;
        "seminaires")
            module="seminaire"
            icon="🎯"
            primary="pink-500"
            ;;
        "ceintures")
            module="ceinture"
            icon="🥋"
            primary="orange-500"
            ;;
        "paiements")
            module="paiement"
            icon="💰"
            primary="yellow-500"
            ;;
        "presences")
            module="presence"
            icon="✅"
            primary="teal-500"
            ;;
        *)
            module="dashboard"
            icon="📊"
            primary="blue-500"
            ;;
    esac
    
    # Titre selon l'action
    case $action in
        "index")
            title="Gestion des $(echo $module_dir | sed 's/.*/\u&/')"
            subtitle="Administration des $module_dir du système"
            ;;
        "create")
            title="Créer $(echo $module | sed 's/.*/\u&/')"
            subtitle="Ajouter un nouveau $(echo $module)"
            ;;
        "edit")
            title="Modifier $(echo $module | sed 's/.*/\u&/')"
            subtitle="Modification des informations"
            ;;
        "show")
            title="Détails $(echo $module | sed 's/.*/\u&/')"
            subtitle="Informations détaillées"
            ;;
        *)
            title="$action $(echo $module | sed 's/.*/\u&/')"
            subtitle="Vue $action du module $module"
            ;;
    esac
    
    echo "📝 Conversion: $view_name ($module)"
    
    # Backup
    cp "$file" "${file}.backup"
    
    # Ajouter x-module-header si pas déjà présent
    if ! grep -q "x-module-header" "$file"; then
        # Trouver la ligne @section('content') et ajouter après
        if grep -q "@section('content')" "$file"; then
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
            echo "✅ $view_name converti"
        else
            echo "⚠️ $view_name: pas de @section('content') trouvé"
        fi
    else
        echo "ℹ️ $view_name déjà converti"
    fi
done

echo ""
echo "📊 Résultat final:"
total_admin=$(find resources/views/admin -name "*.blade.php" | wc -l)
with_header=$(find resources/views/admin -name "*.blade.php" -exec grep -l "x-module-header" {} \; | wc -l)
echo "x-module-header: $with_header/$total_admin vues admin"

# Objectif: 42/42
if [ "$with_header" -eq "$total_admin" ]; then
    echo "🎉 OBJECTIF ATTEINT: 100% des vues admin utilisent x-module-header !"
else
    echo "⚠️ Vues restantes sans x-module-header:"
    find resources/views/admin -name "*.blade.php" -exec grep -L "x-module-header" {} \;
fi

