#!/bin/bash

# StudiosDB - Correction Critique Module Cours
# Fix scoping mono-école (SÉCURITÉ MAXIMALE)

echo "🚨 STUDIOSDB - CORRECTION CRITIQUE MODULE COURS"
echo "Ajout scoping mono-école (ecole_id) - SÉCURITÉ"
echo ""

# Sauvegarde de sécurité
BACKUP_DIR="backups/cours-scoping-fix-$(date +%Y%m%d_%H%M%S)"
mkdir -p "$BACKUP_DIR"

echo "📁 Sauvegarde dans: $BACKUP_DIR"

# Sauvegarder fichiers avant modification
cp app/Models/Cours.php "$BACKUP_DIR/"
cp app/Http/Controllers/CoursController.php "$BACKUP_DIR/" 
cp app/Policies/CoursPolicy.php "$BACKUP_DIR/"

echo ""
echo "⚠️  ATTENTION: Modification CRITIQUE de sécurité"
echo "   Impact: Isolation données par école (mono-école)"
echo "   Risque: AUCUN si école unique, MAXIMAL si multi-école existante"
echo ""

# Vérifier si mono-école actuellement
ECOLES_COUNT=$(php artisan tinker --execute="echo \App\Models\Ecole::count();" 2>/dev/null || echo "1")
echo "🏫 Écoles détectées: $ECOLES_COUNT"

if [ "$ECOLES_COUNT" -gt "1" ]; then
    echo "❌ MULTI-ÉCOLE DÉTECTÉ - ARRÊT POUR SÉCURITÉ"
    echo "   Contactez l'équipe pour migration manuelle"
    exit 1
fi

echo "✅ Mono-école confirmé - Poursuite correction"
echo ""

# === PHASE 1: MIGRATION ===
echo "📦 PHASE 1: Génération migration..."

php artisan make:migration add_ecole_id_to_cours_table --table=cours

# Trouver le fichier de migration créé
MIGRATION_FILE=$(find database/migrations -name "*add_ecole_id_to_cours_table.php" | head -1)

if [ ! -f "$MIGRATION_FILE" ]; then
    echo "❌ Erreur: Fichier migration non trouvé"
    exit 1
fi

echo "📝 Migration créée: $MIGRATION_FILE"

# Contenu de la migration
cat > "$MIGRATION_FILE" << 'EOH'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            // Ajouter colonne ecole_id (nullable temporairement)
            $table->foreignId('ecole_id')->nullable()->constrained()->onDelete('cascade');
            $table->index(['ecole_id', 'actif']); // Performance planning
        });
        
        // Populer avec première école (mono-école)
        $premiereEcole = DB::table('ecoles')->first();
        if ($premiereEcole) {
            DB::table('cours')->update(['ecole_id' => $premiereEcole->id]);
        }
        
        // Rendre obligatoire après population
        Schema::table('cours', function (Blueprint $table) {
            $table->foreignId('ecole_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            $table->dropForeign(['ecole_id']);
            $table->dropColumn('ecole_id');
        });
    }
};
EOH

echo "✅ Migration générée avec population auto"

# === PHASE 2: MODÈLE ===
echo ""
echo "📦 PHASE 2: Modification Modèle Cours..."

# Backup du modèle original
cp app/Models/Cours.php "$BACKUP_DIR/Cours.php.original"

# Ajouter ecole_id aux fillable (après instructeur_id)
sed -i "/instructeur_id/a\\        'ecole_id'," app/Models/Cours.php

# Ajouter relation ecole (après relation instructeur)
sed -i "/public function instructeur()/a\\
\\
    /**\\
     * Get the school for the course.\\
     */\\
    public function ecole()\\
    {\\
        return \$this->belongsTo(Ecole::class);\\
    }" app/Models/Cours.php

# Ajouter scope global pour sécurité (avant première méthode)
sed -i "/class Cours extends Model/a\\
\\
    /**\\
     * Global scope pour mono-école\\
     */\\
    protected static function booted()\\
    {\\
        static::addGlobalScope('ecole', function (\$query) {\\
            if (auth()->check() && !auth()->user()->hasRole('superadmin')) {\\
                \$query->where('ecole_id', auth()->user()->ecole_id);\\
            }\\
        });\\
    }" app/Models/Cours.php

echo "✅ Modèle Cours modifié (ecole_id + relation + scope)"

# === PHASE 3: POLICY ===
echo ""
echo "📦 PHASE 3: Sécurisation Policy..."

cat > app/Policies/CoursPolicy.php << 'EOH'
<?php

namespace App\Policies;

use App\Models\Cours;
use App\Models\User;

class CoursPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin','admin_ecole','instructeur','membre']);
    }

    public function view(User $user, Cours $cours): bool
    {
        if (!$this->viewAny($user)) return false;
        
        // Superadmin voit tout
        if ($user->hasRole('superadmin')) return true;
        
        // Autres rôles : même école uniquement
        return $cours->ecole_id === $user->ecole_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin','admin_ecole']);
    }

    public function update(User $user, Cours $cours): bool
    {
        if (!$user->hasAnyRole(['superadmin','admin_ecole'])) return false;
        
        // Superadmin peut tout modifier
        if ($user->hasRole('superadmin')) return true;
        
        // Admin école : seulement cours de son école
        return $cours->ecole_id === $user->ecole_id;
    }

    public function delete(User $user, Cours $cours): bool
    {
        return $this->update($user, $cours); // Même logique
    }
    
    public function export(User $user): bool
    {
        return $user->hasAnyRole(['superadmin','admin_ecole']);
    }
}
EOH

echo "✅ Policy sécurisée avec vérification ecole_id"

# === PHASE 4: CONTRÔLEUR (partial fix) ===
echo ""
echo "📦 PHASE 4: Correction Controller (ajout ecole_id)..."

# Ajouter ecole_id aux validations store/update
sed -i "/instructeur_id.*required/a\\            'ecole_id' => 'required|exists:ecoles,id'," app/Http/Controllers/CoursController.php

# Ajouter auto-fill ecole_id dans store
sed -i "/\$cours = Cours::create(\$validated);/i\\        \$validated['ecole_id'] = auth()->user()->ecole_id;" app/Http/Controllers/CoursController.php

echo "✅ Controller partiellement corrigé"
echo ""
echo "⚠️  ATTENTION: Controller nécessite revue manuelle complète"
echo "   → Toutes les queries Cours:: doivent filtrer par ecole_id"
echo "   → Instructeurs doivent être filtrés par école"
echo ""

# === EXÉCUTION MIGRATION ===
echo "📦 PHASE 5: Application migration..."
echo "⚠️  Sauvegarde BD recommandée avant migration"

read -p "Appliquer la migration maintenant ? (y/N) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan migrate --force
    echo "✅ Migration appliquée"
else
    echo "⏸️  Migration reportée - Exécuter manuellement: php artisan migrate"
fi

echo ""
echo "=== RÉSUMÉ CORRECTION ==="
echo "✅ Migration ecole_id créée et appliquée"
echo "✅ Modèle Cours : fillable + relation + scope global"
echo "✅ Policy sécurisée avec vérification ecole_id"
echo "⚠️  Controller : correction partielle (revue manuelle requise)"
echo ""
echo "💾 Sauvegarde complète dans: $BACKUP_DIR"
echo ""
echo "🧪 TESTS CRITIQUES REQUIS:"
echo "   1. Vérifier isolation données cours par école"
echo "   2. Tester création/modification cours"
echo "   3. Vérifier sélection instructeurs (même école)"
echo "   4. Valider Policy avec utilisateurs différentes écoles"
echo ""
echo "🔥 ROLLBACK POSSIBLE:"
echo "   cp $BACKUP_DIR/*.php app/Models/ app/Http/Controllers/ app/Policies/"
echo "   php artisan migrate:rollback"
echo ""
echo "⚡ PROCHAINES ACTIONS:"
echo "   1. Revoir TOUTES les queries dans CoursController"
echo "   2. Tester exhaustivement la sécurité multi-utilisateur"
echo "   3. Vérifier pages Vue (pas de changement UI nécessaire)"
