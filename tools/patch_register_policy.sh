#!/usr/bin/env bash
set -euo pipefail
F=app/Providers/AuthServiceProvider.php
[ -f "$F" ] || { echo "❌ $F introuvable"; exit 1; }
if ! grep -q "Membre::class" "$F"; then
  sed -i "/protected \$policies = \[/a \ \ \ \ \\App\\Models\\Membre::class => \\App\\Policies\\MembrePolicy::class," "$F"
  echo "✅ Mapping MembrePolicy enregistré."
else
  echo "ℹ️ Mapping MembrePolicy déjà présent."
fi
