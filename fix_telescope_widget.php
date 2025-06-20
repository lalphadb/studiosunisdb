<?php

$file = 'resources/views/admin/partials/telescope-widget.blade.php';

if (file_exists($file)) {
    $content = file_get_contents($file);
    
    // Remplacer la ligne problématique
    $content = str_replace(
        "fetch('{{ route(\"admin.telescope.stats\") }}')",
        "fetch('{{ route(\"admin.telescope.stats\") }}').catch(() => console.log('Telescope stats not available'))",
        $content
    );
    
    // Ajouter une vérification de sécurité
    $content = str_replace(
        '{{-- JavaScript pour les actions --}}',
        '{{-- JavaScript pour les actions --}}
@if(Route::has("admin.telescope.stats"))',
        $content
    );
    
    $content = str_replace(
        '</script>',
        '</script>
@endif',
        $content
    );
    
    file_put_contents($file, $content);
    echo "✅ Widget Telescope corrigé\n";
} else {
    echo "❌ Widget Telescope non trouvé\n";
}
