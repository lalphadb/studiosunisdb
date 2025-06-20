<?php

$file = 'app/Models/Membre.php';
$content = file_get_contents($file);

// Ajouter la relation ceintureActuelle si elle n'existe pas
if (!strpos($content, 'ceintureActuelle')) {
    // Trouver la fin des relations existantes
    $relationPosition = strrpos($content, 'public function');
    
    if ($relationPosition) {
        $newRelation = '
    /**
     * Ceinture actuelle du membre (la plus récente)
     */
    public function ceintureActuelle()
    {
        return $this->hasOne(MembreCeinture::class)
            ->latest(\'date_obtention\')
            ->with(\'ceinture\');
    }

    /**
     * Toutes les ceintures du membre
     */
    public function membresCeintures()
    {
        return $this->hasMany(MembreCeinture::class);
    }

    ';
    
        // Insérer avant la dernière accolade
        $content = substr_replace($content, $newRelation, -1, 0);
        file_put_contents($file, $content);
        echo "✅ Relations ceintures ajoutées au modèle Membre\n";
    }
}
