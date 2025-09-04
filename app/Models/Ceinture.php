<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ceinture extends Model
{
    use HasFactory;
    
    protected $table = 'ceintures';
    
    protected $fillable = [
        'ordre',
        'nom',
        'nom_en',
        'couleur_hex',
        'description',
        'criteres_passage',
        'est_active',
    ];
    
    protected $casts = [
        'ordre' => 'integer',
        'criteres_passage' => 'array',
        'est_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * Relations
     */
    public function membres(): HasMany
    {
        return $this->hasMany(Membre::class, 'ceinture_actuelle_id');
    }
    
    public function progressionsActuelles(): HasMany
    {
        return $this->hasMany(ProgressionCeinture::class, 'ceinture_actuelle_id');
    }
    
    public function progressionsCibles(): HasMany
    {
        return $this->hasMany(ProgressionCeinture::class, 'ceinture_cible_id');
    }
    
    public function examens(): HasMany
    {
        return $this->hasMany(Examen::class, 'ceinture_id');
    }
    
    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('est_active', true);
    }
    
    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre');
    }
    
    /**
     * Attributs calculés
     */
    public function getNomCompletAttribute(): string
    {
        if ($this->nom_en) {
            return "{$this->nom} ({$this->nom_en})";
        }
        return $this->nom;
    }
    
    public function getEstDebutanteAttribute(): bool
    {
        return $this->ordre <= 2; // Blanche, Jaune, Orange
    }
    
    public function getEstIntermediaireAttribute(): bool
    {
        return $this->ordre > 2 && $this->ordre <= 5; // Verte, Bleue, Violette
    }
    
    public function getEstAvanceeAttribute(): bool
    {
        return $this->ordre > 5; // Brune, Noire et +
    }
    
    /**
     * Obtenir la ceinture suivante
     */
    public function suivante(): ?self
    {
        return static::active()
            ->where('ordre', '>', $this->ordre)
            ->ordered()
            ->first();
    }
    
    /**
     * Obtenir la ceinture précédente
     */
    public function precedente(): ?self
    {
        return static::active()
            ->where('ordre', '<', $this->ordre)
            ->orderBy('ordre', 'desc')
            ->first();
    }
    
    /**
     * Vérifier si un membre peut passer à cette ceinture
     */
    public function peutEtreAtteintePar(Membre $membre): bool
    {
        // Le membre doit avoir la ceinture précédente
        $precedente = $this->precedente();
        
        if (!$precedente) {
            // C'est la première ceinture (blanche)
            return !$membre->ceinture_actuelle_id;
        }
        
        return $membre->ceinture_actuelle_id === $precedente->id;
    }
    
    /**
     * Obtenir les critères de passage formatés
     */
    public function getCriteresFormatesAttribute(): array
    {
        if (!$this->criteres_passage) {
            return $this->getDefaultCriteres();
        }
        
        return $this->criteres_passage;
    }
    
    /**
     * Critères par défaut selon le niveau
     */
    private function getDefaultCriteres(): array
    {
        $base = [
            'temps_minimum' => '3 mois',
            'presences_requises' => 20,
            'examen_theorique' => false,
            'examen_pratique' => true,
        ];
        
        if ($this->est_debutante) {
            return array_merge($base, [
                'temps_minimum' => '2 mois',
                'presences_requises' => 15,
            ]);
        }
        
        if ($this->est_intermediaire) {
            return array_merge($base, [
                'temps_minimum' => '4 mois',
                'presences_requises' => 30,
                'examen_theorique' => true,
            ]);
        }
        
        if ($this->est_avancee) {
            return array_merge($base, [
                'temps_minimum' => '6 mois',
                'presences_requises' => 50,
                'examen_theorique' => true,
                'competition_requise' => true,
            ]);
        }
        
        return $base;
    }
    
    /**
     * Obtenir le style CSS pour affichage
     */
    public function getStyleAttribute(): array
    {
        return [
            'background-color' => $this->couleur_hex,
            'color' => $this->getContrastColor($this->couleur_hex),
            'border' => $this->couleur_hex === '#FFFFFF' ? '1px solid #ccc' : 'none',
        ];
    }
    
    /**
     * Calculer la couleur de contraste pour le texte
     */
    private function getContrastColor($hexColor): string
    {
        // Convertir hex en RGB
        $hex = str_replace('#', '', $hexColor);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Calculer la luminance
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        
        // Retourner noir ou blanc selon la luminance
        return $luminance > 0.5 ? '#000000' : '#FFFFFF';
    }
    
    /**
     * Seeds des ceintures de base
     */
    public static function seedDefaults(): void
    {
        $ceintures = [
            ['ordre' => 0, 'nom' => 'Blanche', 'nom_en' => 'White', 'couleur_hex' => '#FFFFFF'],
            ['ordre' => 1, 'nom' => 'Jaune', 'nom_en' => 'Yellow', 'couleur_hex' => '#FFD700'],
            ['ordre' => 2, 'nom' => 'Orange', 'nom_en' => 'Orange', 'couleur_hex' => '#FFA500'],
            ['ordre' => 3, 'nom' => 'Verte', 'nom_en' => 'Green', 'couleur_hex' => '#00FF00'],
            ['ordre' => 4, 'nom' => 'Bleue', 'nom_en' => 'Blue', 'couleur_hex' => '#0000FF'],
            ['ordre' => 5, 'nom' => 'Violette', 'nom_en' => 'Purple', 'couleur_hex' => '#800080'],
            ['ordre' => 6, 'nom' => 'Brune', 'nom_en' => 'Brown', 'couleur_hex' => '#8B4513'],
            ['ordre' => 7, 'nom' => 'Noire', 'nom_en' => 'Black', 'couleur_hex' => '#000000'],
            ['ordre' => 8, 'nom' => 'Noire 1er Dan', 'nom_en' => 'Black 1st Dan', 'couleur_hex' => '#000000'],
            ['ordre' => 9, 'nom' => 'Noire 2e Dan', 'nom_en' => 'Black 2nd Dan', 'couleur_hex' => '#000000'],
            ['ordre' => 10, 'nom' => 'Noire 3e Dan', 'nom_en' => 'Black 3rd Dan', 'couleur_hex' => '#000000'],
        ];
        
        foreach ($ceintures as $ceinture) {
            static::updateOrCreate(
                ['ordre' => $ceinture['ordre']],
                array_merge($ceinture, ['est_active' => true])
            );
        }
    }
}
