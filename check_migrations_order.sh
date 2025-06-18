#!/bin/bash
echo "🔍 ANALYSE ORDRE MIGRATIONS StudiosUnisDB"
echo "=============================================="

echo ""
echo "📋 ORDRE ACTUEL:"
ls -1 database/migrations/*.php | sort | nl

echo ""
echo "🔗 DÉPENDANCES FOREIGN KEYS:"
echo "1. users (base Laravel)"
echo "2. ecoles (référencé par users.ecole_id)"
echo "3. membres (référence ecoles.id)"
echo "4. cours (référence ecoles.id)"
echo "5. ceintures (autonome)"
echo "6. membre_ceintures (référence membres.id + ceintures.id)"
echo "7. seminaires (référence ecoles.id)"
echo "8. cours_horaires (référence cours.id)"
echo "9. inscriptions_cours (référence membres.id + cours.id)"
echo "10. inscriptions_seminaires (référence membres.id + seminaires.id)"
echo "11. presences (référence membres.id + cours.id)"
echo "12. paiements (référence membres.id + ecoles.id + users.id)"
echo "13. permissions (Spatie - autonome)"
echo "14. activity_log (Spatie - référence users.id)"

echo ""
echo "⚠️  PROBLÈMES POTENTIELS:"
grep -n "foreign\|references\|constrained" database/migrations/*.php | grep -v "comment"
