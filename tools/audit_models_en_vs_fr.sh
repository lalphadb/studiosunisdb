#!/usr/bin/env bash
set -euo pipefail

echo "== Audit modèles EN restants (Member/Course/Attendance/Payment/Belt) =="
rg -n 'App\\Models\\(Member|Course|Attendance|Payment|Belt)\b' app resources || true

echo
echo "== Comptes par modèle =="
for m in Member Course Attendance Payment Belt; do
  c=$(rg -n "App\\\\Models\\\\$m\\b" app resources | wc -l | awk '{print $1}')
  printf "  %-11s : %s\n" "$m" "$c"
done

cat <<'TIP'
Tips:
- Utilisez ripgrep avec guillemets simples pour éviter les fuites d'échappement du shell.
- Ajoutez -P si vous voulez les non-capturing groups (?: ... ).
TIP
