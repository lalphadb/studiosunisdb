#!/usr/bin/env bash
set -euo pipefail

F="app/Providers/AuthServiceProvider.php"
[[ -f "$F" ]] || { echo "❌ $F introuvable"; exit 1; }

cp "$F" "${F}.bak.$(date +%F_%H%M%S)"

# 1) S'assurer des imports
grep -q "^use App\\\Models\\\Membre;" "$F" || sed -i "1s|^|use App\\\Models\\\Membre;\n|" "$F"
grep -q "^use App\\\Policies\\\MembrePolicy;" "$F" || sed -i "1s|^|use App\\\Policies\\\MembrePolicy;\n|" "$F"

# 2) Nettoyer d'anciens mappings EN si présents
sed -i "/MemberPolicy::class/d" "$F"
sed -i "/App\\\\Models\\\\Member::class/d" "$F"

# 3) S'assurer que la propriété \$policies existe
if ! grep -q "protected \$policies" "$F"; then
  sed -i "/class AuthServiceProvider extends ServiceProvider/a \ \ \ \ protected \$policies = [\n\ \ \ \ ];\n" "$F"
fi

# 4) Ajouter (une seule fois) le mapping Membre => MembrePolicy
if ! grep -q "Membre::class => MembrePolicy::class" "$F"; then
  # insère dans le tableau $policies
  awk '
    BEGIN{pol_open=0}
    /protected \$policies *= *\[/ {print; pol_open=1; next}
    pol_open==1 && /\]/ && !done {
      print "        \\App\\Models\\Membre::class => \\App\\Policies\\MembrePolicy::class,";
      done=1; pol_open=0; print; next
    }
    {print}
  ' "$F" > "$F.tmp" && mv "$F.tmp" "$F"
fi

# 5) (Optionnel) Gate::before pour superadmin
if ! grep -q "Gate::before(function" "$F"; then
  awk '
    /public function boot/ && !done {
      print;
      print "        \\Illuminate\\Support\\Facades\\Gate::before(function ($user, $ability) {";
      print "            return $user->hasRole(\"superadmin\") ? true : null;";
      print "        });";
      done=1; next
    }
    {print}
  ' "$F" > "$F.tmp" && mv "$F.tmp" "$F"
fi

composer dump-autoload -o >/dev/null 2>&1 || true
php artisan optimize:clear >/dev/null 2>&1 || true

echo "✅ AuthServiceProvider corrigé."
