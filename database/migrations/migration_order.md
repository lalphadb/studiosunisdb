# ORDRE OPTIMAL DES MIGRATIONS - StudiosUnisDB

## 1. TABLES DE BASE (pas de dépendances)
0001_01_01_000000_create_users_table.php
0001_01_01_000001_create_cache_table.php  
0001_01_01_000002_create_jobs_table.php
2025_06_18_230000_create_permission_tables.php

## 2. TABLES PRINCIPALES
2025_06_18_200100_create_ecoles_table.php
2025_06_18_200800_create_ceintures_system.php (NOUVELLE)

## 3. EXTENSIONS USERS
2025_06_18_200300_add_fields_to_users_table.php

## 4. TABLES DÉPENDANTES
2025_06_18_200500_create_cours_table.php
2025_06_18_200600_create_seminaires_table.php
2025_06_18_200700_create_cours_horaires_table.php

## 5. TABLES DE LIAISON
2025_06_18_200900_create_inscriptions_cours_table.php
2025_06_18_201000_create_inscriptions_seminaires_table.php
2025_06_18_201100_create_presences_table.php
2025_06_18_201200_create_paiements_table.php

## 6. LOGS & MONITORING
2025_06_18_230100_create_activity_log_table.php

## 7. DÉVELOPPEMENT (optionnel)
2025_06_19_124116_create_telescope_entries_table.php
