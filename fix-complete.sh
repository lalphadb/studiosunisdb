#!/bin/bash

# =====================================================
# STUDIOSDB - FIX COMPLET DES MIGRATIONS
# =====================================================

echo "╔══════════════════════════════════════════╗"
echo "║   FIX COMPLET - MIGRATIONS & SETUP        ║"
echo "╚══════════════════════════════════════════╝"
echo ""

cd /home/studiosdb/studiosunisdb

# 1. Clear tous les caches
echo "🧹 Nettoyage complet des caches..."
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
echo ""

# 2. Créer la table ecoles directement via SQL si elle n'existe pas
echo "📦 Création de la table ecoles si nécessaire..."
php artisan tinker --execute="
if (!\Schema::hasTable('ecoles')) {
    DB::statement('
        CREATE TABLE IF NOT EXISTS ecoles (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            adresse VARCHAR(255),
            ville VARCHAR(255),
            code_postal VARCHAR(10),
            province VARCHAR(50) DEFAULT \"QC\",
            telephone VARCHAR(20),
            email VARCHAR(255),
            site_web VARCHAR(255),
            logo VARCHAR(255),
            config JSON,
            actif BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            INDEX idx_slug (slug),
            INDEX idx_actif (actif)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ');
    echo '✅ Table ecoles créée';
} else {
    echo '✓ Table ecoles existe déjà';
}"
echo ""

# 3. Ajouter ecole_id aux tables qui en ont besoin
echo "📦 Ajout de ecole_id aux tables..."
for table in users membres cours presences paiements ceintures examens families progression_ceintures factures; do
    php artisan tinker --execute="
    if (\Schema::hasTable('$table')) {
        if (!\Schema::hasColumn('$table', 'ecole_id')) {
            \Schema::table('$table', function (\$t) {
                \$t->unsignedBigInteger('ecole_id')->nullable()->after('id')->index();
            });
            echo '✅ ecole_id ajouté à $table';
        } else {
            echo '✓ $table a déjà ecole_id';
        }
    }" 2>/dev/null || true
done
echo ""

# 4. Marquer les migrations problématiques comme exécutées si nécessaire
echo "🔧 Marquage des migrations problématiques..."
php artisan tinker --execute="
// Vérifier si la migration des indexes est marquée comme exécutée
\$migration = DB::table('migrations')->where('migration', '2025_08_20_000001_add_indexes_to_membres')->first();
if (!\$migration) {
    // Vérifier si les indexes existent déjà
    \$hasIndexes = false;
    if (\Schema::hasTable('membres')) {
        \$indexes = DB::select('SHOW INDEX FROM membres');
        foreach (\$indexes as \$idx) {
            if (strpos(\$idx->Key_name, 'statut_date_derniere_presence') !== false) {
                \$hasIndexes = true;
                break;
            }
        }
    }
    
    if (\$hasIndexes) {
        // Marquer comme exécutée si les indexes existent déjà
        DB::table('migrations')->insert([
            'migration' => '2025_08_20_000001_add_indexes_to_membres',
            'batch' => DB::table('migrations')->max('batch') + 1
        ]);
        echo '✅ Migration indexes marquée comme exécutée';
    }
}"
echo ""

# 5. Exécuter les migrations restantes
echo "🔄 Exécution des migrations restantes..."
php artisan migrate --force || true
echo ""

# 6. Créer l'école par défaut
echo "🏢 Création de l'école par défaut..."
php artisan tinker --execute="
use App\Models\Ecole;
\$ecole = Ecole::where('slug', 'studios-unis-st-emile')->first();
if (!\$ecole) {
    \$ecole = Ecole::create([
        'nom' => 'Studios Unis St-Émile',
        'slug' => 'studios-unis-st-emile',
        'adresse' => '1234 Rue Principale',
        'ville' => 'Québec',
        'code_postal' => 'G1A 1A1',
        'province' => 'QC',
        'telephone' => '(418) 555-0123',
        'email' => 'info@studiosunisstemile.ca',
        'site_web' => 'https://studiosunisstemile.ca',
        'actif' => true,
        'config' => json_encode([
            'couleur_primaire' => '#3B82F6',
            'couleur_secondaire' => '#10B981',
            'inscription_ouverte' => true,
            'tarif_mensuel_defaut' => 75,
            'tarif_cours_defaut' => 15,
        ])
    ]);
    echo '✅ École créée: ' . \$ecole->nom;
    
    // Mettre à jour les enregistrements sans ecole_id
    DB::table('users')->whereNull('ecole_id')->update(['ecole_id' => \$ecole->id]);
    DB::table('membres')->whereNull('ecole_id')->update(['ecole_id' => \$ecole->id]);
    echo PHP_EOL . '✅ Enregistrements mis à jour avec ecole_id';
} else {
    echo '✓ École existe déjà: ' . \$ecole->nom;
}"
echo ""

# 7. Vérifications finales
echo "✅ Vérifications finales..."
php artisan tinker --execute="
echo 'Table ecoles: ' . (\Schema::hasTable('ecoles') ? '✅ OK' : '❌ ERREUR') . PHP_EOL;
echo 'École par défaut: ' . (App\Models\Ecole::count() > 0 ? '✅ ' . App\Models\Ecole::first()->nom : '❌ AUCUNE') . PHP_EOL;
echo 'Users avec ecole_id: ' . DB::table('users')->whereNotNull('ecole_id')->count() . PHP_EOL;
echo 'Membres avec ecole_id: ' . DB::table('membres')->whereNotNull('ecole_id')->count();"
echo ""

# 8. Optimisation
echo "⚡ Optimisation finale..."
php artisan config:cache
php artisan route:cache
echo ""

echo "╔══════════════════════════════════════════╗"
echo "║   ✅ CORRECTIONS APPLIQUÉES !             ║"
echo "╚══════════════════════════════════════════╝"
echo ""
echo "Prochaine étape: npm run build"
