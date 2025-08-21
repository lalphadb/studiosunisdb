#!/usr/bin/env bash
set -euo pipefail

ROOT="$(pwd)"
BACKUP_DIR="backups/cleanup_$(date +%F_%H%M%S)"
APPLY=0
[[ "${1:-}" == "--apply" ]] && APPLY=1

say() { printf "%b\n" "$*"; }
hr(){ printf "%*s\n" 80 | tr ' ' '-'; }

targets_dirs=(app resources routes database tests config)
grep_scope=$(IFS=' '; echo "${targets_dirs[*]}")

# --- helpers
refcount() {
  local Class="$1"
  # cherche Class, use Class;, Class::, new Class(
  grep -RInE "(use[[:space:]]+.*\\b${Class}\\b|\\b${Class}::|new[[:space:]]+${Class}\\b|\\b${Class}\\b\\()" ${grep_scope} 2>/dev/null | wc -l || true
}

maybe_mv(){
  local file="$1"
  [[ -f "$file" ]] || return 0
  if [[ $APPLY -eq 1 ]]; then
    mkdir -p "$BACKUP_DIR"
    say "  → move $file  →  $BACKUP_DIR/"
    mkdir -p "$BACKUP_DIR/$(dirname "$file")"
    git mv -f "$file" "$BACKUP_DIR/$(dirname "$file")/" 2>/dev/null || mv -f "$file" "$BACKUP_DIR/$(dirname "$file")/"
  else
    say "  (dry-run) would move: $file"
  fi
}

hr
say "StudiosDB - Audit & Cleanup (dry-run=${APPLY})"
hr

# 1) Paires FR/EN de modèles
declare -A pairs=(
  [Membre]=app/Models/Member.php
  [Cours]=app/Models/Course.php
  [Ceinture]=app/Models/Belt.php
  [Presence]=app/Models/Attendance.php
  [Paiement]=app/Models/Payment.php
)

say "➡️  Audit des modèles FR/EN"
for fr in "${!pairs[@]}"; do
  en_path="${pairs[$fr]}"
  fr_path="app/Models/${fr}.php"
  en="${en_path##*/}"; en="${en%.php}"
  fr_refs=$(refcount "$fr")
  en_refs=$(refcount "$en")
  say "  - ${fr} vs ${en}: refs FR=${fr_refs} | EN=${en_refs}"
  if [[ -f "$en_path" && "$en_refs" -eq 0 ]]; then
    say "    ✅ $en_path n'est pas référencé → candidat à archive"
    maybe_mv "$en_path"
  fi
done
hr

# 2) Requests doublons
say "➡️  Audit Requests doublons"
keep_dir="app/Http/Requests/Membres"
mapfile -t req_dupes < <(ls app/Http/Requests 2>/dev/null | grep -E '^(Member(Store|Update)Request|Membre(Store|Update)Request|StoreMembreRequest|UpdateMembreRequest)\.php' || true)
for f in "${req_dupes[@]:-}"; do
  [[ -n "$f" ]] || continue
  # si existe aussi sous /Membres, on archive celui en racine
  if [[ -f "$keep_dir/StoreMembreRequest.php" || -f "$keep_dir/UpdateMembreRequest.php" ]]; then
    maybe_mv "app/Http/Requests/$f"
  fi
done
hr

# 3) Policies
say "➡️  Audit Policies"
if [[ -f app/Policies/MemberPolicy.php && -f app/Policies/MembrePolicy.php ]]; then
  refs_member=$(refcount "MemberPolicy")
  [[ "$refs_member" -eq 0 ]] && maybe_mv app/Policies/MemberPolicy.php
fi
hr

# 4) Controllers backups/old
say "➡️  Archive des backups/old dans Controllers"
find app/Http/Controllers -maxdepth 1 -type f \( -name "*.bak.*" -o -name "*.backup.*" -o -name "*.old" \) | while read -r f; do
  maybe_mv "$f"
done
hr

# 5) Fichier parasite ziggy
say "➡️  Recherche fichier parasite ziggy"
find resources/js -maxdepth 1 -type f -name "*ziggy-js*" | while read -r f; do
  say "    ⚠️ trouvé: $f"
  maybe_mv "$f"
done
hr

# 6) Seeders doublons évidents (Ceintures/Belts)
say "➡️  Seeders en doublon (Ceintures/Belts)"
for s in database/seeders/BeltsSeeder.php database/seeders/CeinturesSeeder.php database/seeders/CeintureSeeder.php; do
  [[ -f "$s" ]] && say "  - $(basename "$s") présent"
done
say "  (revérifie DatabaseSeeder.php pour n'en appeler qu'un seul)"
hr

if [[ $APPLY -eq 1 ]]; then
  say "✅ Apply terminé. Sauvegardes dans: $BACKUP_DIR"
else
  say "ℹ️  Rien n'a été déplacé (dry-run). Relance avec:  tools/cleanup_duplicates.sh --apply"
fi
