#!/bin/bash

echo "🔍 VÉRIFICATION LAYOUT APP"
echo "=========================="

echo "Contenu actuel de app.blade.php:"
echo "================================"
head -20 resources/views/layouts/app.blade.php

echo ""
echo "Vérification footer inclus:"
grep -n "footer" resources/views/layouts/app.blade.php

echo ""
echo "✅ Layout app vérifié"
