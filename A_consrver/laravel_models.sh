# 🏗️ CRÉATION MODÈLES LARAVEL 12.21 - STUDIOSDB V5 PRO

# Modèle Membre (PRINCIPAL)
cat > app/Models/Membre.php << 'EOH'
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, Relations\BelongsTo, Relations\HasMany, Relations\BelongsToMany};
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

/**
 * Modèle Membre - StudiosDB v5 Pro
 * 
 * @property int $id
 * @property int $user_id
 * @property string $prenom
 * @property string $nom
 * @property Carbon $date_naissance
 * @property string $sexe
 * @property string|null $telephone
 * @property string|null $adresse
 * @property string|null $contact_urgence_nom
 * @property string|null $contact_urgence_telephone
 * @property string $statut
 * @property int|null $ceinture_actuelle_id
 * @property Carbon $date_inscription
 * @property Carbon|null $date_derniere_presence
 * @property string|null $notes_medicales
 * @property bool $consentement_photos
 * @property bool $consentement_communications
 */
final class Membre extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'prenom',
        'nom',
        'date_naissance',
        'sexe',
        'telephone',
        'adresse',
        'contact_urgence_nom',
        'contact_urgence_telephone',
        'statut',
        'ceinture_actuelle_id',
        'date_inscription',
        'date_derniere_presence',
        'notes_medicales',
        'consentement_photos',
        'consentement_communications',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_inscription' => 'date',
        'date_derniere_presence' => 'date',
        'consentement_photos' => 'boolean',
        'consentement_communications' => 'boolean',
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ceintureActuelle(): BelongsTo
    {
        return $this->belongsTo(Ceinture::class, 'ceinture_actuelle_id');
    }

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    public function cours(): BelongsToMany
    {
        return $this->belongsToMany(Cours::class, 'cours_membres')
                    ->withPivot(['date_inscription', 'date_fin', 'statut'])
                    ->withTimestamps();
    }

    // Accesseurs
    public function nomComplet(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->prenom} {$this->nom}"
        );
    }

    public function age(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->date_naissance->age
        );
    }

    // Scopes
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeParCeinture($query, int $ceintureId)
    {
        return $query->where('ceinture_actuelle_id', $ceintureId);
    }

    public function scopeRecents($query, int $jours = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($jours));
    }
}
EOH

# Modèle Ceinture
cat > app/Models/Ceinture.php << 'EOH'
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, Relations\HasMany};

/**
 * Modèle Ceinture - Système de grades
 */
final class Ceinture extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'couleur_hex',
        'ordre',
        'description',
        'prerequis_techniques',
        'duree_minimum_mois',
        'presences_minimum',
        'actif',
    ];

    protected $casts = [
        'prerequis_techniques' => 'array',
        'actif' => 'boolean',
    ];

    // Relations
    public function membres(): HasMany
    {
        return $this->hasMany(Membre::class, 'ceinture_actuelle_id');
    }

    // Scopes
    public function scopeActives($query)
    {
        return $query->where('actif', true);
    }

    public function scopeParOrdre($query)
    {
        return $query->orderBy('ordre');
    }
}
EOH

# Modèle Cours
cat > app/Models/Cours.php << 'EOH'
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, Relations\BelongsTo, Relations\HasMany, Relations\BelongsToMany};

/**
 * Modèle Cours - Gestion des cours et horaires
 */
final class Cours extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'instructeur_id',
        'niveau',
        'age_min',
        'age_max',
        'places_max',
        'jour_semaine',
        'heure_debut',
        'heure_fin',
        'date_debut',
        'date_fin',
        'tarif_mensuel',
        'actif',
    ];

    protected $casts = [
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'tarif_mensuel' => 'decimal:2',
        'actif' => 'boolean',
    ];

    // Relations
    public function instructeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructeur_id');
    }

    public function membres(): BelongsToMany
    {
        return $this->belongsToMany(Membre::class, 'cours_membres')
                    ->withPivot(['date_inscription', 'date_fin', 'statut'])
                    ->withTimestamps();
    }

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    // Scopes
    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    public function scopeParJour($query, string $jour)
    {
        return $query->where('jour_semaine', $jour);
    }

    public function scopeParInstructeur($query, int $instructeurId)
    {
        return $query->where('instructeur_id', $instructeurId);
    }
}
EOH

# Modèle Presence
cat > app/Models/Presence.php << 'EOH'
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, Relations\BelongsTo};

/**
 * Modèle Presence - Suivi des présences
 */
final class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'cours_id',
        'membre_id',
        'instructeur_id',
        'date_cours',
        'statut',
        'heure_arrivee',
        'notes',
    ];

    protected $casts = [
        'date_cours' => 'date',
        'heure_arrivee' => 'datetime:H:i',
    ];

    // Relations
    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }

    public function membre(): BelongsTo
    {
        return $this->belongsTo(Membre::class);
    }

    public function instructeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructeur_id');
    }

    // Scopes
    public function scopePresents($query)
    {
        return $query->where('statut', 'present');
    }

    public function scopeParPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_cours', [$dateDebut, $dateFin]);
    }
}
EOH

# Modèle Paiement
cat > app/Models/Paiement.php << 'EOH'
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, Relations\BelongsTo};

/**
 * Modèle Paiement - Gestion financière
 */
final class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'membre_id',
        'type',
        'montant',
        'description',
        'date_echeance',
        'date_paiement',
        'statut',
        'methode_paiement',
        'reference_transaction',
        'notes',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_echeance' => 'date',
        'date_paiement' => 'date',
    ];

    // Relations
    public function membre(): BelongsTo
    {
        return $this->belongsTo(Membre::class);
    }

    // Scopes
    public function scopeEnRetard($query)
    {
        return $query->where('statut', 'en_retard')
                     ->where('date_echeance', '<', now());
    }

    public function scopePayes($query)
    {
        return $query->where('statut', 'paye');
    }

    public function scopeParType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
EOH

# Extension du modèle User
cat > app/Models/User.php << 'EOH'
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\{HasOne, HasMany};
use Spatie\Permission\Traits\HasRoles;

/**
 * Modèle User étendu - StudiosDB v5
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relations StudiosDB
    public function membre(): HasOne
    {
        return $this->hasOne(Membre::class);
    }

    public function coursInstructeur(): HasMany
    {
        return $this->hasMany(Cours::class, 'instructeur_id');
    }

    public function presencesInstructeur(): HasMany
    {
        return $this->hasMany(Presence::class, 'instructeur_id');
    }

    // Helper methods
    public function estMembre(): bool
    {
        return $this->hasRole('membre');
    }

    public function estInstructeur(): bool
    {
        return $this->hasRole('instructeur');
    }

    public function estAdmin(): bool
    {
        return $this->hasRole('admin');
    }
}
EOH

echo "✅ Tous les modèles Laravel 12.21 ont été créés!"
echo "📋 Relations et fonctionnalités avancées implémentées"
