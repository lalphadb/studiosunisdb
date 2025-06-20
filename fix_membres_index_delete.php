<?php

// Vérifier si le fichier existe
$file = 'resources/views/admin/membres/index.blade.php';

if (file_exists($file)) {
    $content = file_get_contents($file);
    
    // Ajouter un exemple de bouton de suppression s'il n'existe pas
    if (!strpos($content, 'delete-membre')) {
        echo "Le fichier membres/index.blade.php existe mais nécessite une modification manuelle pour la suppression.\n";
        echo "Ajoutez ce code dans les actions de chaque membre :\n\n";
        
        echo '<form method="POST" action="{{ route(\'admin.membres.destroy\', $membre) }}" 
              onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer ce membre ?\')" 
              class="inline">
            @csrf
            @method(\'DELETE\')
            <button type="submit" class="text-red-600 hover:text-red-900 ml-2" title="Supprimer">
                🗑️ Supprimer
            </button>
        </form>' . "\n\n";
    }
} else {
    echo "❌ Le fichier resources/views/admin/membres/index.blade.php n'existe pas\n";
    echo "Créez d'abord la vue index des membres\n";
}
