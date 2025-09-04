#!/bin/bash
# Correction des problèmes MCP
mysql -u root studiosdb << 'SQL'
-- Correction des données orphelines
DELETE FROM cours_membres 
WHERE cours_id NOT IN (SELECT id FROM cours);

DELETE FROM presences 
WHERE cours_id NOT IN (SELECT id FROM cours_legacy);
SQL
echo "Corrections appliquées"
