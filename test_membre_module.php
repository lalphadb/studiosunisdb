#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

// Test MembreRequest
try {
    $request = new \App\Http\Requests\MembreRequest();
    echo "✅ MembreRequest chargé avec succès\n";
    echo "   Règles: " . count($request->rules()) . "\n";
} catch (Exception $e) {
    echo "❌ Erreur MembreRequest: " . $e->getMessage() . "\n";
}

// Test MembreResource
try {
    $resource = new \App\Http\Resources\MembreResource(new \App\Models\Membre());
    echo "✅ MembreResource chargé avec succès\n";
} catch (Exception $e) {
    echo "❌ Erreur MembreResource: " . $e->getMessage() . "\n";
}

// Test trait BelongsToEcole
try {
    $membre = new \App\Models\Membre();
    if (method_exists($membre, 'scopeWithoutEcoleScope')) {
        echo "✅ Trait BelongsToEcole appliqué au modèle Membre\n";
    } else {
        echo "❌ Trait BelongsToEcole non appliqué\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur Trait: " . $e->getMessage() . "\n";
}

// Test Export
try {
    $export = new \App\Exports\MembersExport();
    echo "✅ MembersExport chargé avec succès\n";
} catch (Exception $e) {
    echo "❌ Erreur Export: " . $e->getMessage() . "\n";
}

// Test modèles
$models = ['Ecole', 'Ceinture', 'LienFamilial', 'ProgressionCeinture'];
foreach ($models as $model) {
    try {
        $class = "\\App\\Models\\{$model}";
        new $class();
        echo "✅ Modèle {$model} chargé avec succès\n";
    } catch (Exception $e) {
        echo "❌ Erreur {$model}: " . $e->getMessage() . "\n";
    }
}
