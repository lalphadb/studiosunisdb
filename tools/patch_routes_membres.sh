#!/usr/bin/env bash
set -euo pipefail
F=routes/web.php
grep -q "MembreController::class" "$F" || {
  echo "⚠️ MembreController non référencé dans $F. Ajoute ta Resource si besoin."
}
# Ajout export/bulk/changer-ceinture s'ils sont absents
grep -q "membres.export" "$F" || echo "Route::get('/membres/export', [\\App\\Http\\Controllers\\MembreController::class, 'export'])->name('membres.export');" >> "$F"
grep -q "membres.bulk" "$F" || echo "Route::post('/membres/bulk', [\\App\\Http\\Controllers\\MembreController::class, 'bulk'])->name('membres.bulk');" >> "$F"
grep -q "membres.changer-ceinture" "$F" || echo "Route::post('/membres/{membre}/changer-ceinture', [\\App\\Http\\Controllers\\MembreController::class, 'changerCeinture'])->name('membres.changer-ceinture');" >> "$F"
echo "✅ Routes membres (export/bulk/ceinture) patchées si nécessaire."
