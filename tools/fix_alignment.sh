#!/usr/bin/env bash
set -euo pipefail

APPLY="${1:-}"
apply() { [ "$APPLY" = "--apply" ]; }

say() { echo "— $*"; }

# 0) Préchecks
[ -f composer.json ] || { echo "❌ composer.json manquant"; exit 1; }

# 1) Débrancher tenancy / livewire si présents
if grep -qi 'stancl/tenancy' composer.json; then
  say "Tenancy détecté -> composer remove stancl/tenancy"
  apply && composer remove stancl/tenancy --no-interaction || true
fi
if grep -qi 'livewire' composer.json; then
  say "Livewire détecté -> composer remove livewire/*"
  apply && composer remove livewire/livewire --no-interaction || true
fi

# 2) S'assurer de Spatie Permission
if ! grep -qi 'spatie/laravel-permission' composer.json; then
  say "Ajout spatie/laravel-permission"
  apply && composer require spatie/laravel-permission:^6.0 --no-interaction
fi

# 3) Générer seed rôles si absent
SEED=database/seeders/RoleSeeder.php
if [ ! -f "$SEED" ]; then
  say "Créer $SEED (rôles: superadmin, admin_ecole, instructeur, membre)"
  apply && cat <<'PHP' > "$SEED"
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
            // Membres
            'membres.view','membres.create','membres.edit','membres.delete','membres.export',
            // Utilisateurs
            'users.view','users.create','users.edit','users.delete','users.reset',
            // Cours (réf. fonctionnelle)
            'cours.view','cours.create','cours.edit','cours.delete',
        ];

        foreach ($perms as $p) {
            Permission::findOrCreate($p, 'web');
        }

        // Attributions minimales (exemple) — à affiner par projet
        Role::findByName('superadmin')->givePermissionTo($perms);
        Role::findByName('admin_ecole')->givePermissionTo($perms);
        Role::findByName('instructeur')->givePermissionTo(['membres.view','cours.view']);
        // membre: aucune permission d'admin
    }
}
PHP
fi

# 4) Ajout scoping ecole_id (trait + scope + policy Membre)
mkdir -p app/Models/Scopes app/Models/Concerns app/Policies

SCOPE=app/Models/Scopes/EcoleScope.php
if [ ! -f "$SCOPE" ]; then
  say "Créer $SCOPE"
  apply && cat <<'PHP' > "$SCOPE"
<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class EcoleScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();
        if ($user && isset($model->getAttributes()['ecole_id']) || \Schema::hasColumn($model->getTable(), 'ecole_id')) {
            if ($user->ecole_id) {
                $builder->where($model->getTable().'.ecole_id', $user->ecole_id);
            }
        }
    }
}
PHP
fi

TRAIT=app/Models/Concerns/BelongsToEcole.php
if [ ! -f "$TRAIT" ]; then
  say "Créer $TRAIT"
  apply && cat <<'PHP' > "$TRAIT"
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

POL=app/Policies/MembrePolicy.php
if [ ! -f "$POL" ]; then
  say "Créer $POL"
  apply && cat <<'PHP' > "$POL"
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
        return $user->hasAnyRole('superadmin','admin_ecole','instructeur') && $user->ecole_id === $m->ecole_id
            || ($user->hasRole('membre') && $m->user_id === $user->id);
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

# 5) Migration add ecole_id (sécurisée)
MIG="database/migrations/2025_08_18_000001_add_ecole_id_core.php"
if [ ! -f "$MIG" ]; then
  say "Créer migration $MIG"
  apply && cat <<'PHP' > "$MIG"
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        foreach (['users','membres'] as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'ecole_id')) {
                Schema::table($table, function (Blueprint $t) {
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
                    if (Schema::hasColumn($t->getTable(), 'ecole_id')) {
                        $t->dropConstrainedForeignId('ecole_id');
                    }
                });
            }
        }
    }
};
PHP
fi

echo "✅ Préparation terminée."
if ! apply; then
  echo "ℹ️ Exécute à blanc. Pour appliquer réellement :"
  echo "   bash tools/fix_alignment.sh --apply"
fi
