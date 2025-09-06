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
        'order',
        'name',
        'name_en',
        'color_hex',
        'description',
        'technical_requirements',
        'minimum_duration_months',
        'minimum_attendances',
        'active',
    ];
    
    protected $casts = [
        'order' => 'integer',
        'technical_requirements' => 'array',
        'minimum_duration_months' => 'integer',
        'minimum_attendances' => 'integer',
        'active' => 'boolean',
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
        return $query->where('active', true);
    }
    
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
    
    /**
     * Attributs calculés
     */
    public function getNomAttribute(): string
    {
        return $this->name ?? '';
    }
    
    public function getCouleurHexAttribute(): string
    {
        return $this->color_hex ?? '#000000';
    }
    
    public function getNomCompletAttribute(): string
    {
        $name = $this->name ?? '';
        if ($this->name_en) {
            return "{$name} ({$this->name_en})";
        }
        return $name;
    }
    
    public function getEstDebutanteAttribute(): bool
    {
        return $this->order <= 2; // Blanche, Jaune, Orange
    }
    
    public function getEstIntermediaireAttribute(): bool
    {
        return $this->order > 2 && $this->order <= 5; // Verte, Bleue, Violette
    }
    
    public function getEstAvanceeAttribute(): bool
    {
        return $this->order > 5; // Brune, Noire et +
    }
    
    /**
     * Obtenir la ceinture suivante
     */
    public function suivante(): ?self
    {
        return static::active()
            ->where('order', '>', $this->order)
            ->ordered()
            ->first();
    }
    
    /**
     * Obtenir la ceinture précédente
     */
    public function precedente(): ?self
    {
        return static::active()
            ->where('order', '<', $this->order)
            ->orderBy('order', 'desc')
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
        $base = [
            'temps_minimum' => $this->minimum_duration_months . ' mois',
            'presences_requises' => $this->minimum_attendances,
            'examen_theorique' => $this->est_intermediaire || $this->est_avancee,
            'examen_pratique' => true,
        ];
        
        if ($this->technical_requirements) {
            $base['techniques_requises'] = $this->technical_requirements;
        }
        
        if ($this->est_avancee) {
            $base['competition_requise'] = true;
        }
        
        return $base;
    }
    
    /**
     * Obtenir le style CSS pour affichage
     */
    public function getStyleAttribute(): array
    {
        return [
            'background-color' => $this->color_hex,
            'color' => $this->getContrastColor($this->color_hex),
            'border' => $this->color_hex === '#FFFFFF' ? '1px solid #ccc' : 'none',
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
}
