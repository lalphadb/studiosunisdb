#!/usr/bin/env bash
set -euo pipefail

ok(){ printf "✅ %b\n" "$*"; }
warn(){ printf "⚠️  %b\n" "$*"; }
err(){ printf "❌ %b\n" "$*"; }

# 1) Références EN (doivent être 0)
if rg -n 'App\\Models\\(Member|Course|Attendance|Payment|Belt)\\b' app resources routes 2>/dev/null; then
  err "Références EN trouvées (voir ci-dessus)."
else
  ok "Aucune référence EN (Member/Course/Attendance/Payment/Belt)."
fi

# 2) Layout spacing: seul le layout gère le décalage (ml-72/pl-72)
LAY="resources/js/Layouts/AuthenticatedLayout.vue"
if rg -n "ml-72|pl-72" "$LAY" >/dev/null 2>&1; then
  ok "Décalage latéral géré dans le layout ($LAY)."
else
  warn "Pas de ml-72/pl-72 détecté dans le layout (vérifie l'espace latéral)."
fi

if rg -n 'pl-72' resources/js/Pages/Membres 2>/dev/null; then
  err "pl-72 détecté dans les pages Membres (retire-le, le layout s'en charge)."
else
  ok "Pas de pl-72 dans resources/js/Pages/Membres."
fi

# 3) Routes clés
if php artisan route:list | grep -E '^ *GET *\| *HEAD +dashboard' >/dev/null; then
  ok "Route /dashboard présente."
else
  err "Route /dashboard manquante."
fi

if php artisan route:list | grep -E 'membres(\.|/|$)' >/dev/null; then
  ok "Routes membres présentes."
else
  warn "Aucune route 'membres' détectée ?"
fi

# 4) Policies: mapping Membre -> MembrePolicy
ASP="app/Providers/AuthServiceProvider.php"
if grep -q "Membre::class => \\App\\Policies\\MembrePolicy::class" "$ASP" 2>/dev/null; then
  ok "AuthServiceProvider mappe MembrePolicy."
else
  warn "AuthServiceProvider: mapping MembrePolicy manquant ?"
fi

# 5) Export Excel
if test -f app/Exports/MembersExport.php; then
  ok "Export Excel présent: app/Exports/MembersExport.php"
else
  warn "Export Excel manquant (app/Exports/MembersExport.php)."
fi

# 6) Activitylog (Loi 25 - traçabilité)
if composer show spatie/laravel-activitylog >/dev/null 2>&1; then
  ok "spatie/laravel-activitylog installé."
else
  warn "spatie/laravel-activitylog non installé (recommandé)."
fi

echo "== Check terminé =="
