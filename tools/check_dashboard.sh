#!/usr/bin/env bash
set -euo pipefail

echo "== StudiosDB - Check Dashboard (closure OU controller) =="

# 1) La route existe ?
php artisan route:list | grep -E '(^| )dashboard( |$)' >/dev/null \
  && echo "✅ route /dashboard présente" \
  || { echo "❌ route /dashboard absente"; exit 1; }

# 2) La route est-elle en controller ou en closure ?
if grep -q "DashboardController::class, 'index'" routes/web.php; then
  MODE="controller"
  echo "ℹ️ mapping: controller"
else
  MODE="closure"
  echo "ℹ️ mapping: closure"
fi

# 3) Vue Inertia ciblée
VIEW="-"
if [[ "$MODE" == "controller" ]]; then
  # Indétectable sans parser le controller, on affiche juste un rappel
  echo "👉 Mode controller: assure-toi que Inertia::render('Dashboard/Admin') OU 'Dashboard' est utilisé dans DashboardController"
else
  # Closure: on tente d'extraire la vue de Inertia::render('...')
  VIEW=$(grep -nE "Route::get\(['\"]/dashboard['\"][^;]*" -n routes/web.php -n \
    | sed -n '1,20p' \
    | sed -n '1,120p')
  VIEW=$(grep -oE "Inertia::render\(['\"][^'\"]+['\"]" routes/web.php | head -n1 | sed "s/Inertia::render('//; s/\"//g")
  [[ -z "$VIEW" ]] && VIEW="(non détectée)"
  echo "👉 Vue Inertia détectée (closure): $VIEW"
fi

# 4) Fichiers attendus
test -f resources/js/Layouts/AuthenticatedLayout.vue \
  && echo "✅ Layout trouvé" || echo "❌ Layout manquant"

if [[ "$MODE" == "controller" ]]; then
  test -f resources/js/Pages/Dashboard/Admin.vue \
    && echo "✅ Admin.vue présent" \
    || echo "⚠️ Admin.vue manquant (ok si le controller rend 'Dashboard.vue')"
else
  test -f resources/js/Pages/Dashboard.vue \
    && echo "✅ Dashboard.vue présent (mode closure)" \
    || echo "❌ Dashboard.vue manquant"
fi

# 5) Spacing latéral
grep -qE "ml-72|pl-72|lg:ml-72|lg:pl-72" resources/js/Layouts/AuthenticatedLayout.vue \
  && echo "✅ Spacing latéral OK" \
  || echo "⚠️ Vérifie le spacing latéral dans AuthenticatedLayout"

echo "== done =="
