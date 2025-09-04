#!/usr/bin/env bash
set -Eeuo pipefail

# ────────────────────────────────────────────────────────────────────────────────
# StudiosDB — DB Audit Pro (MySQL/MariaDB + Laravel 12)
# Analyse non-destructive : migrations, tables, FKs, orphelins, index, pivots,
# cohérence belts/ceintures, Loi 25 (audit_logs/consentements).
# ────────────────────────────────────────────────────────────────────────────────

# Couleurs
RED=$'\e[31m'; YEL=$'\e[33m'; GRN=$'\e[32m'; BLU=$'\e[34m'; BLD=$'\e[1m'; RST=$'\e[0m'

ROOT="$(pwd)"
ARTISAN="$ROOT/artisan"
ENV_FILE="$ROOT/.env"
DATE_TAG="$(date +%Y%m%d-%H%M%S)"
OUT_DIR="$ROOT/storage/app/db-audit/$DATE_TAG"
mkdir -p "$OUT_DIR"

err() { echo "${RED}✗${RST} $*" >&2; }
ok()  { echo "${GRN}✓${RST} $*"; }
info(){ echo "${BLU}ℹ${RST}  $*"; }
warn(){ echo "${YEL}!${RST}  $*"; }

req_bin() {
  command -v "$1" >/dev/null 2>&1 || { err "Binaire requis manquant: $1"; exit 2; }
}

# 0) Vérifs préalables
req_bin php
req_bin grep
req_bin awk
req_bin sed
req_bin cut
req_bin sort
req_bin comm
req_bin mysql || true

[[ -f "$ARTISAN" ]] || { err "artisan introuvable dans $ROOT"; exit 2; }
[[ -f "$ENV_FILE" ]] || { err ".env introuvable"; exit 2; }

# 1) Lecture .env (DB_*)
#    On évite "source .env" (peut contenir des espaces/quotes). On parse proprement.
get_env() {
  local key="$1"
  local val
  val="$(grep -E "^${key}=" "$ENV_FILE" | tail -n1 | cut -d= -f2-)"
  # Strip quotes
  val="${val%\"}"; val="${val#\"}"
  val="${val%\'}"; val="${val#\'}"
  printf "%s" "$val"
}

DB_CONNECTION="$(get_env DB_CONNECTION)"
DB_HOST="$(get_env DB_HOST)"
DB_PORT="$(get_env DB_PORT)"
DB_DATABASE="$(get_env DB_DATABASE)"
DB_USERNAME="$(get_env DB_USERNAME)"
DB_PASSWORD="$(get_env DB_PASSWORD)"

[[ -z "${DB_CONNECTION:-}" ]] && DB_CONNECTION="mysql"
[[ -z "${DB_HOST:-}"      ]] && DB_HOST="127.0.0.1"
[[ -z "${DB_PORT:-}"      ]] && DB_PORT="3306"

if ! command -v mysql >/dev/null 2>&1; then
  err "mysql CLI requis. Installe: sudo apt-get install -y mysql-client"
  exit 2
fi

export MYSQL_PWD="$DB_PASSWORD"
MYSQL=( mysql -N -B -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" "$DB_DATABASE" )

# 2) En-tête rapport
REPORT="$OUT_DIR/report.md"
{
  echo "# Audit BDD — $DB_DATABASE ($DATE_TAG)"
  echo ""
  echo "- Hôte: \`$DB_HOST:$DB_PORT\`"
  echo "- Utilisateur: \`$DB_USERNAME\`"
  echo "- Connexion: \`$DB_CONNECTION\`"
  echo "- Projet: $ROOT"
  echo ""
} > "$REPORT"

info "Génère le rapport dans $OUT_DIR"

# 3) État des migrations Laravel
info "Analyse des migrations Laravel…"
MIG_STATUS="$OUT_DIR/migrations_status.txt"
php "$ARTISAN" migrate:status --no-interaction --ansi | tee "$MIG_STATUS" >/dev/null || true

# Liste des fichiers de migrations (FS)
mapfile -t FS_MIGS < <(ls database/migrations/*.php 2>/dev/null | xargs -n1 basename | sed 's/\.php$//' | sort)
printf "%s\n" "${FS_MIGS[@]}" > "$OUT_DIR/migrations_fs.txt"

# Liste des migrations appliquées (DB)
mapfile -t DB_MIGS < <("${MYSQL[@]}" -e "SELECT migration FROM migrations ORDER BY migration;" || true)
printf "%s\n" "${DB_MIGS[@]}" > "$OUT_DIR/migrations_db.txt"

comm -23 "$OUT_DIR/migrations_fs.txt" "$OUT_DIR/migrations_db.txt" > "$OUT_DIR/migrations_pending.txt" || true
comm -13 "$OUT_DIR/migrations_fs.txt" "$OUT_DIR/migrations_db.txt" > "$OUT_DIR/migrations_unknown_in_db.txt" || true

PENDING_CNT=$(wc -l < "$OUT_DIR/migrations_pending.txt" | tr -d ' ')
UNKNOWN_CNT=$(wc -l < "$OUT_DIR/migrations_unknown_in_db.txt" | tr -d ' ')

{
  echo "## Migrations"
  echo "- En attente (FS non appliquées): **$PENDING_CNT**"
  echo "- Inconnues (présentes en DB mais pas sur le FS): **$UNKNOWN_CNT**"
  echo ""
} >> "$REPORT"

# 4) Inventaire tables
info "Inventaire des tables…"
TABLES_CSV="$OUT_DIR/tables.csv"
"${MYSQL[@]}" -e "
SELECT
  t.TABLE_NAME,
  t.ENGINE,
  t.TABLE_ROWS,
  ROUND((t.DATA_LENGTH + t.INDEX_LENGTH)/1024/1024,2) AS MB,
  t.TABLE_COLLATION
FROM information_schema.TABLES t
WHERE t.TABLE_SCHEMA = DATABASE()
ORDER BY MB DESC;
" > "$TABLES_CSV"

TABLE_CNT=$(($(wc -l < "$TABLES_CSV")))
echo "## Tables ($((TABLE_CNT)))" >> "$REPORT"
echo "" >> "$REPORT"
echo "Fichier: \`tables.csv\`" >> "$REPORT"
echo "" >> "$REPORT"

# 5) Clés étrangères + règles ON DELETE/UPDATE
info "Extraction des FKs…"
FKS_CSV="$OUT_DIR/fks.csv"
"${MYSQL[@]}" -e "
SELECT
  k.CONSTRAINT_NAME,
  k.TABLE_NAME,
  k.COLUMN_NAME,
  k.REFERENCED_TABLE_NAME,
  k.REFERENCED_COLUMN_NAME,
  r.UPDATE_RULE,
  r.DELETE_RULE
FROM information_schema.KEY_COLUMN_USAGE k
JOIN information_schema.REFERENTIAL_CONSTRAINTS r
  ON r.CONSTRAINT_SCHEMA = k.TABLE_SCHEMA
 AND r.CONSTRAINT_NAME   = k.CONSTRAINT_NAME
WHERE k.TABLE_SCHEMA = DATABASE()
  AND k.REFERENCED_TABLE_NAME IS NOT NULL
ORDER BY k.TABLE_NAME, k.CONSTRAINT_NAME;" > "$FKS_CSV"

FK_CNT=$(($(wc -l < "$FKS_CSV")))
echo "## Clés étrangères ($((FK_CNT)))" >> "$REPORT"
echo "" >> "$REPORT"
echo "Fichier: \`fks.csv\`" >> "$REPORT"
echo "" >> "$REPORT"

# 6) Orphelins sur toutes les FKs
info "Détection des orphelins… (peut prendre un moment)"
ORPH_CSV="$OUT_DIR/orphans.csv"
echo "table,column,referenced_table,referenced_column,orphans_count" > "$ORPH_CSV"

mapfile -t FKS < <(tail -n +2 "$FKS_CSV")
for row in "${FKS[@]}"; do
  IFS=$'\t' read -r CONSTRAINT_NAME TABLE_NAME COLUMN_NAME REF_TABLE REF_COL UPD_RULE DEL_RULE <<< "$row"
  COUNT=$("${MYSQL[@]}" -e "
    SELECT COUNT(*)
    FROM \`$TABLE_NAME\` c
    LEFT JOIN \`$REF_TABLE\` p ON c.\`$COLUMN_NAME\` = p.\`$REF_COL\`
    WHERE c.\`$COLUMN_NAME\` IS NOT NULL AND p.\`$REF_COL\` IS NULL;
  " | tr -d '\n' || echo "0")
  echo "$TABLE_NAME,$COLUMN_NAME,$REF_TABLE,$REF_COL,$COUNT" >> "$ORPH_CSV"
done

ORPH_CRIT=$(awk -F, 'NR>1 && $5>0 {c++} END{print c+0}' "$ORPH_CSV")
if [[ "$ORPH_CRIT" -gt 0 ]]; then
  warn "Orphelins détectés sur $ORPH_CRIT relation(s)"
else
  ok "Aucun orphelin détecté"
fi

echo "## Orphelins" >> "$REPORT"
echo "" >> "$REPORT"
echo "Fichier: \`orphans.csv\`" >> "$REPORT"
echo "" >> "$REPORT"

# 7) Index manquants pour colonnes FK (première colonne d’index)
info "Vérification des index sur colonnes FK…"
MISSING_FK_IDX="$OUT_DIR/missing_fk_indexes.csv"
echo "table,column,has_index" > "$MISSING_FK_IDX"
for row in "${FKS[@]}"; do
  IFS=$'\t' read -r _ TABLE_NAME COLUMN_NAME REF_TABLE REF_COL _ _ <<< "$row"
  HAS=$("${MYSQL[@]}" -e "
    SELECT COUNT(*) FROM information_schema.STATISTICS s
    WHERE s.TABLE_SCHEMA = DATABASE()
      AND s.TABLE_NAME   = '$TABLE_NAME'
      AND s.COLUMN_NAME  = '$COLUMN_NAME'
      AND s.SEQ_IN_INDEX = 1;
  " | tr -d '\n')
  if [[ "$HAS" -eq 0 ]]; then
    echo "$TABLE_NAME,$COLUMN_NAME,0" >> "$MISSING_FK_IDX"
  fi
done
MISSING_FK_CNT=$(awk -F, 'NR>1 && $3==0 {c++} END{print c+0}' "$MISSING_FK_IDX")

echo "## Index FK manquants: **$MISSING_FK_CNT**" >> "$REPORT"
echo "" >> "$REPORT"
echo "Fichier: \`missing_fk_indexes.csv\`" >> "$REPORT"
echo "" >> "$REPORT"

# 8) Pivots: vérifier contrainte UNIQUE sur paires FK (heuristique)
info "Analyse des pivots (contrainte UNIQUE)…"
PIVOT_UNIQUES="$OUT_DIR/pivot_uniques.csv"
echo "table,fk_cols,has_composite_unique" > "$PIVOT_UNIQUES"

# Heuristique: table avec ≥2 FKs et peu de colonnes non-FK → pivot probable
mapfile -t TABLE_LIST < <("${MYSQL[@]}" -e "SHOW TABLES;")
for T in "${TABLE_LIST[@]}"; do
  mapfile -t FK_COLS < <("${MYSQL[@]}" -e "
    SELECT COLUMN_NAME
    FROM information_schema.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME   = '$T'
      AND REFERENCED_TABLE_NAME IS NOT NULL
    ORDER BY ORDINAL_POSITION;")
  if [[ "${#FK_COLS[@]}" -ge 2 ]]; then
    FK_SET=$(printf "%s," "${FK_COLS[@]}" | sed 's/,$//')
    # Cherche un index UNIQUE couvrant exactement ces colonnes (ordre quelconque)
    HAS_UNIQUE=0
    # Récupère toutes les clés uniques (index_name, columns in order)
    mapfile -t UNIQUES < <("${MYSQL[@]}" -e "
      SELECT s.INDEX_NAME, GROUP_CONCAT(s.COLUMN_NAME ORDER BY s.SEQ_IN_INDEX)
      FROM information_schema.STATISTICS s
      WHERE s.TABLE_SCHEMA = DATABASE()
        AND s.TABLE_NAME   = '$T'
        AND s.NON_UNIQUE=0
      GROUP BY s.INDEX_NAME;")
    for u in "${UNIQUES[@]}"; do
      IDX_COLS="$(echo "$u" | cut -f2)"
      # Compare ensembles (ignorer l'ordre)
      if [[ ",$FK_SET," == *",${IDX_COLS},"* ]] || [[ ",$IDX_COLS," == *",${FK_SET},"* ]]; then
        HAS_UNIQUE=1; break
      fi
    done
    echo "$T,\"$FK_SET\",$HAS_UNIQUE" >> "$PIVOT_UNIQUES"
  fi
done

PIVOT_MISS=$(awk -F, 'NR>1 && $3==0 {c++} END{print c+0}' "$PIVOT_UNIQUES")
echo "## Pivots sans UNIQUE composite: **$PIVOT_MISS**" >> "$REPORT"
echo "" >> "$REPORT"
echo "Fichier: \`pivot_uniques.csv\`" >> "$REPORT"
echo "" >> "$REPORT"

# 9) Recommandations d’index métier (présences, paiements, examens, progression)
info "Contrôle des index recommandés…"
RECO_IDX="$OUT_DIR/recommended_indexes.csv"
echo "table,index,exists" > "$RECO_IDX"

check_index_exists() {
  local t="$1" ; local expected="$2"
  # expected sous forme 'col1,col2' (ordre)
  local cnt
  cnt=$("${MYSQL[@]}" -e "
    SELECT COUNT(*)
    FROM (
      SELECT GROUP_CONCAT(COLUMN_NAME ORDER BY SEQ_IN_INDEX) cols
      FROM information_schema.STATISTICS
      WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$t'
      GROUP BY INDEX_NAME
    ) x
    WHERE x.cols = '$expected';
  " | tr -d '\n')
  [[ "$cnt" -gt 0 ]] && echo 1 || echo 0
}

declare -A WANT
WANT["presences"]="date_presence,cours_id"
WANT["paiements"]="date_paiement,membre_id"
WANT["examens"]="date_examen"
WANT["progression_ceintures"]="membre_id,created_at"

for T in "${!WANT[@]}"; do
  E="${WANT[$T]}"
  EXISTS=$(check_index_exists "$T" "$E")
  echo "$T,$E,$EXISTS" >> "$RECO_IDX"
done

RECO_MISS=$(awk -F, 'NR>1 && $3==0 {c++} END{print c+0}' "$RECO_IDX")
echo "## Index recommandés manquants: **$RECO_MISS**" >> "$REPORT"
echo "" >> "$REPORT"
echo "Fichier: \`recommended_indexes.csv\`" >> "$REPORT"
echo "" >> "$REPORT"

# 10) Cohérence belts / ceintures
BELTS=$("${MYSQL[@]}" -e "SHOW TABLES LIKE 'belts';" | wc -l | tr -d ' ')
CEINT=$("${MYSQL[@]}" -e "SHOW TABLES LIKE 'ceintures';" | wc -l | tr -d ' ')
if [[ "$BELTS" -gt 0 && "$CEINT" -gt 0 ]]; then
  warn "Tables 'belts' ET 'ceintures' coexistent → incohérence de naming."
elif [[ "$BELTS" -gt 0 ]]; then
  warn "Table 'belts' détectée (recommandation: renommer en 'ceintures')."
elif [[ "$CEINT" -gt 0 ]]; then
  ok "Table 'ceintures' détectée (naming FR cohérent)."
fi
echo "## Naming ceintures" >> "$REPORT"
echo "" >> "$REPORT"
echo "- belts: $BELTS ; ceintures: $CEINT" >> "$REPORT"
echo "" >> "$REPORT"

# 11) Conformité Loi 25 — tables clés
info "Vérification Loi 25 (audit_logs, consentements)…"
HAS_AUDIT=$("${MYSQL[@]}" -e "SHOW TABLES LIKE 'audit_logs';" | wc -l | tr -d ' ')
HAS_CONS=$("${MYSQL[@]}" -e "SHOW TABLES LIKE 'consentements';" | wc -l | tr -d ' ')
echo "## Loi 25" >> "$REPORT"
echo "" >> "$REPORT"
if [[ "$HAS_AUDIT" -eq 0 ]]; then
  echo "- ❌ Table \`audit_logs\` absente" >> "$REPORT"
else
  echo "- ✅ Table \`audit_logs\` présente" >> "$REPORT"
fi
if [[ "$HAS_CONS" -eq 0 ]]; then
  echo "- ❌ Table \`consentements\` absente" >> "$REPORT"
else
  echo "- ✅ Table \`consentements\` présente" >> "$REPORT"
fi
echo "" >> "$REPORT"

# 12) Synthèse et anomalies
ANOMALIES="$OUT_DIR/anomalies.csv"
echo "type,detail" > "$ANOMALIES"

[[ "$PENDING_CNT" -gt 0 ]]       && echo "migrations_pending,$PENDING_CNT" >> "$ANOMALIES"
[[ "$UNKNOWN_CNT" -gt 0 ]]       && echo "migrations_unknown,$UNKNOWN_CNT" >> "$ANOMALIES"
[[ "$ORPH_CRIT" -gt 0 ]]         && echo "orphans_relations,$ORPH_CRIT" >> "$ANOMALIES"
[[ "$MISSING_FK_CNT" -gt 0 ]]    && echo "missing_fk_indexes,$MISSING_FK_CNT" >> "$ANOMALIES"
[[ "$PIVOT_MISS" -gt 0 ]]        && echo "pivot_missing_unique,$PIVOT_MISS" >> "$ANOMALIES"
[[ "$RECO_MISS" -gt 0 ]]         && echo "recommended_indexes_missing,$RECO_MISS" >> "$ANOMALIES"
[[ "$BELTS" -gt 0 && "$CEINT" -gt 0 ]] && echo "naming_conflict_belts_ceintures,1" >> "$ANOMALIES"
[[ "$HAS_AUDIT" -eq 0 ]]         && echo "missing_audit_logs,1" >> "$ANOMALIES"
[[ "$HAS_CONS" -eq 0 ]]          && echo "missing_consentements,1" >> "$ANOMALIES"

CRIT=$(awk -F, 'NR>1 {c++} END{print c+0}' "$ANOMALIES")

{
  echo "## Synthèse"
  echo ""
  echo "- Migrations en attente : **$PENDING_CNT**"
  echo "- Migrations inconnues  : **$UNKNOWN_CNT**"
  echo "- Relations orphelines  : **$ORPH_CRIT**"
  echo "- Index FK manquants    : **$MISSING_FK_CNT**"
  echo "- Pivots sans UNIQUE    : **$PIVOT_MISS**"
  echo "- Index reco manquants  : **$RECO_MISS**"
  echo "- Loi 25 — audit_logs   : $([[ $HAS_AUDIT -eq 0 ]] && echo '❌' || echo '✅')"
  echo "- Loi 25 — consentements: $([[ $HAS_CONS  -eq 0 ]] && echo '❌' || echo '✅')"
  echo ""
  echo "Fichier anomalies: \`anomalies.csv\`"
  echo ""
  echo "> Conseils :"
  echo "- Ajouter index FK manquants et UNIQUE sur pivots (ex. cours_membres)." 
  echo "- Mettre en place \`audit_logs\` et \`consentements\` si absents (Loi 25)."
  echo "- Résoudre le naming \`belts/ceintures\` si duplication."
  echo "- Traiter les orphelins (DELETE/SET NULL/CASCADE selon le métier)."
  echo ""
} >> "$REPORT"

if [[ "$CRIT" -gt 0 ]]; then
  warn "Audit terminé avec $CRIT alerte(s). Voir $REPORT"
  exit 1
else
  ok "Audit terminé sans anomalie critique. Voir $REPORT"
fi
