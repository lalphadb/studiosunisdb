#!/bin/bash
echo "🧹 Nettoyage migrations dupliquées"
cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# Supprimer les migrations en double
rm -f database/migrations/2025_07_20_134813_create_ceintures_table.php
rm -f database/migrations/2025_07_20_134813_create_cours_table.php

echo "✅ Migrations dupliquées supprimées"
ls database/migrations/ | grep -E "(ceintures|cours)"
