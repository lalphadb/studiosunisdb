#!/usr/bin/env bash
set -euo pipefail

echo "== StudiosDB v5 - Audit d'alignement (mono-école, UI=Dashboard, Inertia) =="

fail=0
have() { command -v "$1" >/dev/null 2>&1; }

# 1) Composer packages
if [ -f composer.json ]; then
  echo "-- composer.json --"
  jq -r '.require, .["require-dev"] // {} | keys[]' composer.json 2>/dev/null | sort | sed 's/^/   /' || cat composer.json
  if grep -qi 'stancl/tenancy' composer.json; then
    echo "❌ Multi-tenant détecté (stancl/tenancy). Projet cible = mono-école."
    fail=1
  else
    echo "✅ Pas de package tenancy."
  fi
  if grep -qi 'livewire' composer.json; then
    echo "❌ Livewire détecté. Stack cible = Inertia + Vue 3 (pas Livewire)."
    fail=1
  else
    echo "✅ Pas de Livewire."
  fi
  if ! grep -qi 'spatie/laravel-permission' composer.json; then
    echo "❌ spatie/laravel-permission manquant (rôles & permissions)."
    fail=1
  else
    echo "✅ Spatie Permission présent."
  fi
else
  echo "❌ composer.json introuvable"
  fail=1
fi

# 2) Front (Inertia)
if [ -f package.json ]; then
  echo "-- package.json --"
  jq -r '.dependencies, .devDependencies // {} | keys[]' package.json 2>/dev/null | sort | sed 's/^/   /' || cat package.json
  if ! grep -qi '@inertiajs/vue3' package.json; then
    echo "❌ Inertia Vue 3 non détecté."
    fail=1
  else
    echo "✅ Inertia Vue 3 détecté."
  fi
else
  echo "❌ package.json introuvable"
  fail=1
fi

# 3) UI de référence = Dashboard
if [ -d resources/js/Pages/Dashboard ] || [ -f resources/js/Pages/Dashboard/Admin.vue ]; then
  echo "✅ Dashboard présent (référence UI)."
else
  echo "❌ Dashboard (référence UI) introuvable."
  fail=1
fi

# 4) Layout & primitives UI
ls -1 resources/js/Layouts 2>/dev/null | sed 's/^/   /' || true
if [ ! -f resources/js/Layouts/AuthenticatedLayout.vue ]; then
  echo "❌ AuthenticatedLayout.vue manquant."
  fail=1
fi
if [ -d resources/js/Components ]; then
  if ls resources/js/Components | grep -qiE 'StatsCard|ActionCard'; then
    echo "✅ Primitives UI Dashboard détectées (StatsCard/ActionCard)."
  else
    echo "ℹ️ Primitives UI Dashboard non détectées (StatsCard/ActionCard) -> vérifier."
  fi
fi

# 5) Routes & Policies (ecole_id)
if grep -R "ecole_id" -n app database 2>/dev/null | head -n 1 >/dev/null; then
  echo "✅ Traces de scoping ecole_id détectées."
else
  echo "❌ Aucune trace de scoping ecole_id (mono-école requis)."
  fail=1
fi

# 6) Artisan sanity (si dispo)
if have php; then
  if [ -f artisan ]; then
    echo "-- php artisan about --"
    php artisan about || true
  fi
fi

echo
if [ $fail -eq 0 ]; then
  echo "🎉 AUDIT OK : conforme aux invariants (mono-école, Inertia, UI=Dashboard)."
else
  echo "⚠️ AUDIT INCOMPLET : corrections nécessaires."
fi
