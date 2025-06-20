<?php
echo "Ajoutez ces boutons dans resources/views/admin/ceintures/index.blade.php :\n\n";

echo '<div class="flex space-x-4 mb-6">
    <a href="{{ route(\'admin.ceintures.types\') }}" 
       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        📚 Types de Ceintures
    </a>
    
    <a href="{{ route(\'admin.ceintures.attribuer\') }}" 
       class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
        🎯 Attribuer Ceinture
    </a>
    
    <a href="{{ route(\'admin.ceintures.index\') }}" 
       class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700">
        📋 Historique (actuel)
    </a>
</div>' . "\n\n";
