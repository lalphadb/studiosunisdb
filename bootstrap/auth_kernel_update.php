<?php

// Mise à jour du fichier app/Http/Kernel.php pour ajouter le middleware de debug
$kernelPath = app_path('Http/Kernel.php');
$kernelContent = file_get_contents($kernelPath);

// Ajouter le middleware de debug
$middlewareToAdd = "        'debug.auth' => \App\Http\Middleware\DebugAuthMiddleware::class,";

if (strpos($kernelContent, 'debug.auth') === false) {
    $kernelContent = str_replace(
        "protected \$routeMiddleware = [",
        "protected \$routeMiddleware = [\n$middlewareToAdd",
        $kernelContent
    );
    
    file_put_contents($kernelPath, $kernelContent);
    echo "✅ Middleware de debug ajouté au Kernel\n";
} else {
    echo "ℹ️  Middleware de debug déjà présent\n";
}
