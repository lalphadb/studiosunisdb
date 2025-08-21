#!/usr/bin/env bash
set -euo pipefail
F="app/Providers/AuthServiceProvider.php"
[ -f "$F" ] || { echo "❌ $F introuvable"; exit 1; }

# Sauvegarde
cp -v "$F" "$F.bak.$(date +%F_%H%M%S)"

# Ajouter le use si manquant
grep -q "use App\\Models\\Membre;" "$F" || sed -i "1s/^/use App\\\Models\\\Membre;\nuse App\\\Policies\\\MembrePolicy;\n/" "$F"

# Insérer le mapping $policies si absent
if ! grep -q "Membre::class => MembrePolicy::class" "$F"; then
  sed -i "s/protected \$policies = \[/protected \$policies = [\n        Membre::class => MembrePolicy::class,/" "$F"
  echo "✅ Mapping MembrePolicy ajouté."
else
  echo "ℹ️ Mapping MembrePolicy déjà présent."
fi
