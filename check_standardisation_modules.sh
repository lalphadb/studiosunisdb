#!/bin/bash

REPORT_DIR="storage/audit"
mkdir -p "$REPORT_DIR"
REPORT="$REPORT_DIR/standardisation_modules_$(date +%Y%m%d_%H%M%S).log"

echo "📝 Rapport de standardisation des modules (StudiosDB)" > "$REPORT"
echo "Date : $(date)" >> "$REPORT"
echo "=====================================================" >> "$REPORT"

echo -e "\n1️⃣ CONTRÔLE DES VUES ADMIN (présence du standard)" >> "$REPORT"
find resources/views/admin -type f -name "*.blade.php" | while read view; do
    echo -e "\n- $(basename "$view") :" >> "$REPORT"
    if grep -q "@extends('layouts.admin')" "$view"; then
        echo "    ✅ @extends('layouts.admin')" >> "$REPORT"
    else
        echo "    ❌ Manque @extends('layouts.admin')" >> "$REPORT"
    fi
    if grep -q "@include('partials.admin-navigation')" "$view"; then
        echo "    ✅ @include('partials.admin-navigation')" >> "$REPORT"
    else
        echo "    ⚠️  Manque @include('partials.admin-navigation')" >> "$REPORT"
    fi
    if grep -q "x-module-header" "$view"; then
        echo "    ✅ x-module-header présent" >> "$REPORT"
    else
        echo "    ⚠️  Manque x-module-header" >> "$REPORT"
    fi
    if grep -q "x-admin.flash-messages" "$view"; then
        echo "    ✅ x-admin.flash-messages présent" >> "$REPORT"
    else
        echo "    ⚠️  Manque x-admin.flash-messages" >> "$REPORT"
    fi
done

echo -e "\n2️⃣ FICHIERS PARTIALS/LAYOUTS UTILISÉS (où et combien de fois)" >> "$REPORT"
for partial in $(find resources/views/partials -type f -name "*.blade.php"); do
    name=$(basename "$partial")
    count=$(grep -r "@include('partials.${name%.blade.php}')" resources/views/admin | wc -l)
    echo "  - $name : utilisé $count fois" >> "$REPORT"
done
for layout in $(find resources/views/layouts -type f -name "*.blade.php"); do
    name=$(basename "$layout")
    count=$(grep -r "@extends('layouts.${name%.blade.php}')" resources/views/admin | wc -l)
    echo "  - $name : utilisé $count fois" >> "$REPORT"
done

echo -e "\n3️⃣ FICHIERS ORPHELINS (jamais inclus/utilisés)" >> "$REPORT"
ALL_BLADE=$(find resources/views -type f -name "*.blade.php")
USED_BLADE=$(grep -r -E "@include\(|@extends\(|x-[a-zA-Z0-9_.-]+" resources/views | awk -F: '{print $2}' | sed -n "s/.*'\([^']*\)'.*/\1/p" | sed 's/\./\//g' | awk '{print "resources/views/"$1".blade.php"}' | sort | uniq)
for f in $ALL_BLADE; do
    base=$(basename "$f")
    # Si non utilisé dans d'autres vues, ni dans un contrôleur
    if ! grep -rq "$base" resources/views app/Http/Controllers; then
        echo "$f" >> "$REPORT"
    fi
done

echo -e "\n4️⃣ SYNTHÈSE : modules non standardisés" >> "$REPORT"
grep -B1 "❌\|⚠️" "$REPORT" | grep -v "^--$" >> "$REPORT"

echo -e "\n✅ Rapport sauvegardé dans $REPORT"
echo "✅ Analyse terminée."
