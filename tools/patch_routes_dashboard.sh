#!/usr/bin/env bash
set -euo pipefail
R=routes/web.php
[[ -f "$R" ]] || { echo "❌ $R introuvable"; exit 1; }

# use du controller (au début du fichier si manquant)
grep -q "use App\\\Http\\\Controllers\\\DashboardController;" "$R" || \
  sed -i "1i <?php\nuse App\\\Http\\\Controllers\\\DashboardController;" "$R"

# bloc /dashboard (auth+verified) si manquant
grep -q "name('dashboard')" "$R" || cat <<'PHP' >> "$R"

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/dashboard/metriques', [DashboardController::class, 'metriquesTempsReel'])->name('dashboard.metrics');
});
PHP
echo "✅ routes patched."
