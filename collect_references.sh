#!/bin/bash
# collect_references.sh

echo "=== CONTROLLERS RÉFÉRENCE ==="
echo "--- UserController ---"
cat app/Http/Controllers/Admin/UserController.php
echo -e "\n--- EcoleController ---" 
cat app/Http/Controllers/Admin/EcoleController.php

echo -e "\n=== MODÈLES RÉFÉRENCE ==="
echo "--- User Model ---"
cat app/Models/User.php
echo -e "\n--- Ecole Model ---"
cat app/Models/Ecole.php

echo -e "\n=== ROUTES ADMIN ==="
cat routes/admin.php

echo -e "\n=== STRUCTURE DB USERS ==="
cat database/migrations/*_create_users_table.php | head -50
