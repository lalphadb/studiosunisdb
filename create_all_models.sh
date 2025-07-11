#!/bin/bash
echo "🚀 Création de tous les modèles manquants..."

# Modèle UserCeinture
cat > app/Models/UserCeinture.php << 'MODEL'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEcoleScope;

class UserCeinture extends Model
{
    use HasFactory, HasEcoleScope;

    protected $fillable = [
        'user_id',
        'ceinture_id',
        'ecole_id',
        'date_obtention',
        'numero_certificat',
        'evaluateur_id',
        'notes',
    ];

    protected $casts = [
        'date_obtention' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ceinture()
    {
        return $this->belongsTo(Ceinture::class);
    }

    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function evaluateur()
    {
        return $this->belongsTo(User::class, 'evaluateur_id');
    }
}
MODEL

# Modèle CoursHoraire
cat > app/Models/CoursHoraire.php << 'MODEL'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEcoleScope;

class CoursHoraire extends Model
{
    use HasFactory, HasEcoleScope;

    protected $fillable = [
        'cours_id',
        'ecole_id',
        'jour',
        'heure_debut',
        'heure_fin',
        'salle',
        'instructeur_id',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'jour' => 'integer',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
    ];

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function instructeur()
    {
        return $this->belongsTo(User::class, 'instructeur_id');
    }
}
MODEL

# Modèle InscriptionCours
cat > app/Models/InscriptionCours.php << 'MODEL'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEcoleScope;

class InscriptionCours extends Model
{
    use HasFactory, HasEcoleScope;

    protected $table = 'inscriptions_cours';

    protected $fillable = [
        'user_id',
        'cours_id',
        'ecole_id',
        'date_inscription',
        'date_fin',
        'statut',
        'notes',
    ];

    protected $casts = [
        'date_inscription' => 'date',
        'date_fin' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }
}
MODEL

# Modèle Paiement
cat > app/Models/Paiement.php << 'MODEL'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEcoleScope;

class Paiement extends Model
{
    use HasFactory, HasEcoleScope;

    protected $fillable = [
        'user_id',
        'ecole_id',
        'montant',
        'date_paiement',
        'type_paiement',
        'methode_paiement',
        'reference_paiement',
        'statut',
        'description',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function scopeCompletes($query)
    {
        return $query->where('statut', 'complete');
    }
}
MODEL

# Modèle Seminaire
cat > app/Models/Seminaire.php << 'MODEL'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEcoleScope;

class Seminaire extends Model
{
    use HasFactory, HasEcoleScope;

    protected $fillable = [
        'ecole_id',
        'nom',
        'description',
        'date_debut',
        'date_fin',
        'lieu',
        'instructeur_principal_id',
        'prix',
        'capacite_max',
        'statut',
        'ouvert_externe',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'prix' => 'decimal:2',
        'ouvert_externe' => 'boolean',
    ];

    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function instructeurPrincipal()
    {
        return $this->belongsTo(User::class, 'instructeur_principal_id');
    }

    public function inscriptions()
    {
        return $this->hasMany(InscriptionSeminaire::class);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'inscriptions_seminaires')
            ->withPivot('statut', 'date_inscription', 'paiement_id', 'notes')
            ->withTimestamps();
    }
}
MODEL

# Modèle InscriptionSeminaire
cat > app/Models/InscriptionSeminaire.php << 'MODEL'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEcoleScope;

class InscriptionSeminaire extends Model
{
    use HasFactory, HasEcoleScope;

    protected $table = 'inscriptions_seminaires';

    protected $fillable = [
        'seminaire_id',
        'user_id',
        'ecole_id',
        'date_inscription',
        'statut',
        'paiement_id',
        'notes',
    ];

    protected $casts = [
        'date_inscription' => 'date',
    ];

    public function seminaire()
    {
        return $this->belongsTo(Seminaire::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function paiement()
    {
        return $this->belongsTo(Paiement::class);
    }
}
MODEL

echo "✅ Tous les modèles créés!"
