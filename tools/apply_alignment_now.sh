#!/usr/bin/env bash
set -euo pipefail

say(){ echo "— $*"; }
has(){ command -v "$1" >/dev/null 2>&1; }
req(){ has "$1" || { echo "❌ Commande manquante: $1"; exit 1; }; }

[ -f artisan ] || { echo "❌ Lance ce script depuis la racine du projet (artisan introuvable)"; exit 1; }

req php; req composer

# 0) Nettoyage des caches pour éviter des surprises
php artisan optimize:clear || true

# 1) Supprime tenancy s'il est là
if grep -qi '"stancl/tenancy"' composer.json; then
  say "Retrait stancl/tenancy (mono-école requis)…"
  composer remove stancl/tenancy --no-interaction || true
fi

# 2) Assure Spatie Permission
if ! grep -qi '"spatie/laravel-permission"' composer.json; then
  say "Installation spatie/laravel-permission…"
  composer require spatie/laravel-permission:^6.0 --no-interaction
fi

# 3) Fichiers nécessaires (créés seulement s'ils n'existent pas)

# 3.1 Seeder rôles
if [ ! -f database/seeders/RoleSeeder.php ]; then
  say "Création database/seeders/RoleSeeder.php"
  mkdir -p database/seeders
  cat > database/seeders/RoleSeeder.php <<'PHP'
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['superadmin','admin_ecole','instructeur','membre'];
        foreach ($roles as $r) {
            Role::findOrCreate($r, 'web');
        }

        $perms = [
            'membres.view','membres.create','membres.edit','membres.delete','membres.export',
            'users.view','users.create','users.edit','users.delete','users.reset',
            'cours.view','cours.create','cours.edit','cours.delete',
        ];
        foreach ($perms as $p) {
            Permission::findOrCreate($p, 'web');
        }

        Role::findByName('superadmin')->givePermissionTo($perms);
        Role::findByName('admin_ecole')->givePermissionTo($perms);
        Role::findByName('instructeur')->givePermissionTo(['membres.view','cours.view']);
        // membre: aucune permission d'admin
    }
}
PHP
fi

# 3.2 Scope + Trait de scoping par ecole_id
mkdir -p app/Models/Scopes app/Models/Concerns app/Policies

if [ ! -f app/Models/Scopes/EcoleScope.php ]; then
  say "Création app/Models/Scopes/EcoleScope.php"
  cat > app/Models/Scopes/EcoleScope.php <<'PHP'
<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class EcoleScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();
        $table = $model->getTable();

        if ($user && Schema::hasColumn($table, 'ecole_id') && ! empty($user->ecole_id)) {
            $builder->where($table.'.ecole_id', $user->ecole_id);
        }
    }
}
PHP
fi

if [ ! -f app/Models/Concerns/BelongsToEcole.php ]; then
  say "Création app/Models/Concerns/BelongsToEcole.php"
  cat > app/Models/Concerns/BelongsToEcole.php <<'PHP'
<?php

namespace App\Models\Concerns;

use App\Models\Scopes\EcoleScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToEcole
{
    protected static function bootBelongsToEcole(): void
    {
        static::addGlobalScope(new EcoleScope());
        static::creating(function ($model) {
            if (auth()->check() && empty($model->ecole_id)) {
                $model->ecole_id = auth()->user()->ecole_id;
            }
        });
    }

    public function ecole(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Ecole::class);
    }
}
PHP
fi

# 3.3 Policy Membre (créée si absente)
if [ ! -f app/Policies/MembrePolicy.php ]; then
  say "Création app/Policies/MembrePolicy.php"
  cat > app/Policies/MembrePolicy.php <<'PHP'
<?php

namespace App\Policies;

use App\Models\Membre;
use App\Models\User;

class MembrePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole('superadmin','admin_ecole','instructeur');
    }

    public function view(User $user, Membre $m): bool
    {
        if ($user->hasAnyRole('superadmin','admin_ecole','instructeur')) {
            return $user->ecole_id === $m->ecole_id;
        }
        if ($user->hasRole('membre')) {
            return $m->user_id === $user->id;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole('superadmin','admin_ecole');
    }

    public function update(User $user, Membre $m): bool
    {
        return $user->hasAnyRole('superadmin','admin_ecole') && $user->ecole_id === $m->ecole_id;
    }

    public function delete(User $user, Membre $m): bool
    {
        return $user->hasAnyRole('superadmin','admin_ecole') && $user->ecole_id === $m->ecole_id;
    }
}
PHP
fi

# 3.4 Migration ecole_id (ajoute si manquant)
MIG="database/migrations/2025_08_18_000001_add_ecole_id_core.php"
if [ ! -f "$MIG" ]; then
  say "Création $MIG"
  cat > "$MIG" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        foreach (['users','membres'] as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'ecole_id')) {
                Schema::table($table, function (Blueprint $t) use ($table) {
                    $t->unsignedBigInteger('ecole_id')->nullable()->index()->after('id');
                    if (Schema::hasTable('ecoles')) {
                        $t->foreign('ecole_id')->references('id')->on('ecoles')->nullOnDelete();
                    }
                });
            }
        }
    }

    public function down(): void
    {
        foreach (['users','membres'] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'ecole_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropColumn('ecole_id');
                });
            }
        }
    }
};
PHP
fi

# 4) Enregistre la Policy si besoin
if grep -q "class AuthServiceProvider" app/Providers/AuthServiceProvider.php; then
  if ! grep -q "Membre::class" app/Providers/AuthServiceProvider.php; then
    say "Enregistrement MembrePolicy dans AuthServiceProvider"
    sed -i "/protected \$policies = \[/a \ \ \ \ \\App\\Models\\Membre::class => \\App\\Policies\\MembrePolicy::class," app/Providers/AuthServiceProvider.php
  fi
fi

# 5) Patch du modèle Membre pour activer le trait (si fichier présent)
if [ -f app/Models/Membre.php ]; then
  if ! grep -q "BelongsToEcole" app/Models/Membre.php; then
    say "Patch app/Models/Membre.php (use BelongsToEcole)"
    sed -i "1s;^;<?php\n;g" app/Models/Membre.php # s'assure d'un header PHP
    sed -i "/namespace App\\\\Models;/a use App\\\Models\\\Concerns\\\BelongsToEcole;" app/Models/Membre.php
    sed -i "s/class Membre extends/class Membre extends/" app/Models/Membre.php
    # Injecte le trait juste après la signature de classe si non présent
    awk '1;/class Membre extends/ && c==0 {print "    use BelongsToEcole;"; c=1}' app/Models/Membre.php > app/Models/Membre.php.tmp && mv app/Models/Membre.php.tmp app/Models/Membre.php
  fi
fi

# 6) Autoload + migrations + seed
composer dump-autoload
php artisan migrate --force
php artisan db:seed --class="Database\\Seeders\\RoleSeeder"

# 7) Status finaux
php artisan about || true
php artisan route:clear || true
php artisan route:list || true

echo "🎉 Alignement terminé."
