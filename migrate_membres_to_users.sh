#!/bin/bash

# StudiosDB - Migration Membres vers Users
# =========================================
# Ce script unifie les tables membres et users

set -e

echo "╔══════════════════════════════════════════════╗"
echo "║     MIGRATION MEMBRES → USERS                ║"
echo "╚══════════════════════════════════════════════╝"

# Configuration
DB_NAME="studiosdb"
DB_USER="root"
DB_PASS=""
BACKUP_DIR="./backups/migration_$(date +%Y%m%d_%H%M%S)"

# Création du répertoire de backup
mkdir -p "$BACKUP_DIR"

echo ""
echo "📁 Backup de la base de données..."
mysqldump -u$DB_USER $DB_NAME > "$BACKUP_DIR/backup_avant_migration.sql"
echo "✅ Backup créé: $BACKUP_DIR/backup_avant_migration.sql"

echo ""
echo "🔄 Début de la migration..."

# Script SQL de migration
cat > "$BACKUP_DIR/migration.sql" << 'EOF'
-- ========================================
-- MIGRATION MEMBRES VERS USERS
-- ========================================

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Créer les users pour les membres qui n'en ont pas
INSERT INTO users (
    name,
    prenom,
    nom,
    email,
    telephone,
    date_naissance,
    sexe,
    adresse,
    ville,
    code_postal,
    province,
    contact_urgence_nom,
    contact_urgence_telephone,
    contact_urgence_relation,
    password,
    active,
    statut,
    ceinture_actuelle_id,
    date_inscription,
    date_derniere_presence,
    notes_medicales,
    allergies,
    medicaments,
    consentement_photos,
    consentement_communications,
    date_consentement,
    family_id,
    champs_personnalises,
    ecole_id,
    created_at,
    updated_at
)
SELECT 
    CONCAT(m.prenom, ' ', m.nom) as name,
    m.prenom,
    m.nom,
    m.email,
    m.telephone,
    m.date_naissance,
    m.sexe,
    m.adresse,
    m.ville,
    m.code_postal,
    m.province,
    m.contact_urgence_nom,
    m.contact_urgence_telephone,
    m.contact_urgence_relation,
    '$2y$12$defaultPasswordHash123456789012345678901234567890', -- Password temporaire
    CASE WHEN m.statut = 'actif' THEN 1 ELSE 0 END as active,
    m.statut,
    m.ceinture_actuelle_id,
    m.date_inscription,
    m.date_derniere_presence,
    m.notes_medicales,
    m.allergies,
    m.medicaments,
    m.consentement_photos,
    m.consentement_communications,
    m.date_consentement,
    m.family_id,
    m.champs_personnalises,
    m.ecole_id,
    m.created_at,
    m.updated_at
FROM membres m
WHERE m.user_id IS NULL
AND m.deleted_at IS NULL
AND NOT EXISTS (
    SELECT 1 FROM users u WHERE u.email = m.email
);

-- 2. Mettre à jour les users existants avec les données des membres
UPDATE users u
INNER JOIN membres m ON u.id = m.user_id
SET 
    u.prenom = COALESCE(u.prenom, m.prenom),
    u.nom = COALESCE(u.nom, m.nom),
    u.telephone = COALESCE(u.telephone, m.telephone),
    u.date_naissance = COALESCE(u.date_naissance, m.date_naissance),
    u.sexe = m.sexe,
    u.adresse = COALESCE(u.adresse, m.adresse),
    u.ville = COALESCE(u.ville, m.ville),
    u.code_postal = COALESCE(u.code_postal, m.code_postal),
    u.province = m.province,
    u.contact_urgence_nom = COALESCE(u.contact_urgence_nom, m.contact_urgence_nom),
    u.contact_urgence_telephone = COALESCE(u.contact_urgence_telephone, m.contact_urgence_telephone),
    u.contact_urgence_relation = COALESCE(u.contact_urgence_relation, m.contact_urgence_relation),
    u.statut = m.statut,
    u.ceinture_actuelle_id = COALESCE(u.ceinture_actuelle_id, m.ceinture_actuelle_id),
    u.date_inscription = COALESCE(u.date_inscription, m.date_inscription),
    u.date_derniere_presence = COALESCE(u.date_derniere_presence, m.date_derniere_presence),
    u.notes_medicales = COALESCE(u.notes_medicales, m.notes_medicales),
    u.allergies = COALESCE(u.allergies, m.allergies),
    u.medicaments = COALESCE(u.medicaments, m.medicaments),
    u.consentement_photos = m.consentement_photos,
    u.consentement_communications = m.consentement_communications,
    u.date_consentement = COALESCE(u.date_consentement, m.date_consentement),
    u.family_id = COALESCE(u.family_id, m.family_id),
    u.champs_personnalises = COALESCE(u.champs_personnalises, m.champs_personnalises)
WHERE m.deleted_at IS NULL;

-- 3. Créer une table de mapping pour tracer la migration
CREATE TABLE IF NOT EXISTS migration_membres_users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    membre_id BIGINT UNSIGNED,
    user_id BIGINT UNSIGNED,
    migrated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_membre (membre_id),
    INDEX idx_user (user_id)
);

-- 4. Enregistrer le mapping
INSERT INTO migration_membres_users (membre_id, user_id)
SELECT m.id, COALESCE(m.user_id, u.id) as user_id
FROM membres m
LEFT JOIN users u ON u.email = m.email
WHERE m.deleted_at IS NULL;

-- 5. Attribuer le rôle 'membre' à tous les users migrés
INSERT IGNORE INTO model_has_roles (role_id, model_type, model_id)
SELECT 
    r.id as role_id,
    'App\\Models\\User' as model_type,
    u.id as model_id
FROM users u
INNER JOIN migration_membres_users mmu ON u.id = mmu.user_id
CROSS JOIN roles r
WHERE r.name = 'membre'
AND r.guard_name = 'web';

-- 6. Mettre à jour les références dans les autres tables
-- Presences
UPDATE presences p
INNER JOIN migration_membres_users mmu ON p.membre_id = mmu.membre_id
SET p.membre_id = mmu.user_id
WHERE EXISTS (SELECT 1 FROM migration_membres_users WHERE membre_id = p.membre_id);

-- Paiements
UPDATE paiements p
INNER JOIN migration_membres_users mmu ON p.membre_id = mmu.membre_id
SET p.membre_id = mmu.user_id
WHERE EXISTS (SELECT 1 FROM migration_membres_users WHERE membre_id = p.membre_id);

-- Progressions ceintures
UPDATE progressions_ceintures pc
INNER JOIN migration_membres_users mmu ON pc.membre_id = mmu.membre_id
SET pc.membre_id = mmu.user_id
WHERE EXISTS (SELECT 1 FROM migration_membres_users WHERE membre_id = pc.membre_id);

-- Liens familiaux
UPDATE liens_familiaux lf
INNER JOIN migration_membres_users mmu1 ON lf.membre_parent_id = mmu1.membre_id
SET lf.membre_parent_id = mmu1.user_id
WHERE EXISTS (SELECT 1 FROM migration_membres_users WHERE membre_id = lf.membre_parent_id);

UPDATE liens_familiaux lf
INNER JOIN migration_membres_users mmu2 ON lf.membre_enfant_id = mmu2.membre_id
SET lf.membre_enfant_id = mmu2.user_id
WHERE EXISTS (SELECT 1 FROM migration_membres_users WHERE membre_id = lf.membre_enfant_id);

SET FOREIGN_KEY_CHECKS = 1;

-- Rapport de migration
SELECT 
    'Migration terminée' as status,
    (SELECT COUNT(*) FROM migration_membres_users) as membres_migres,
    (SELECT COUNT(*) FROM users WHERE ecole_id IS NOT NULL) as total_users,
    (SELECT COUNT(*) FROM membres WHERE deleted_at IS NULL) as total_membres_origine;
EOF

echo "📝 Script de migration créé"

# Exécution de la migration
mysql -u$DB_USER $DB_NAME < "$BACKUP_DIR/migration.sql" 2>&1 | tee "$BACKUP_DIR/migration.log"

echo ""
echo "✅ Migration terminée!"

# Vérification post-migration
echo ""
echo "📊 Vérification post-migration:"
mysql -u$DB_USER $DB_NAME -e "
SELECT 
    'Users total' as type, COUNT(*) as count FROM users
UNION ALL
SELECT 'Users avec ecole_id', COUNT(*) FROM users WHERE ecole_id IS NOT NULL
UNION ALL
SELECT 'Membres originaux', COUNT(*) FROM membres WHERE deleted_at IS NULL
UNION ALL
SELECT 'Mapping créé', COUNT(*) FROM migration_membres_users
UNION ALL
SELECT 'Users avec rôle membre', COUNT(DISTINCT model_id) 
FROM model_has_roles 
WHERE model_type = 'App\\\\Models\\\\User' 
AND role_id IN (SELECT id FROM roles WHERE name = 'membre');
"

echo ""
echo "╔══════════════════════════════════════════════╗"
echo "║     MIGRATION COMPLÉTÉE AVEC SUCCÈS          ║"
echo "╚══════════════════════════════════════════════╝"
echo ""
echo "📁 Backup disponible: $BACKUP_DIR/backup_avant_migration.sql"
echo ""
echo "⚠️  IMPORTANT:"
echo "   - Les membres ont été migrés vers la table users"
echo "   - Un mot de passe temporaire a été assigné aux nouveaux users"
echo "   - La table de mapping 'migration_membres_users' conserve la trace"
echo "   - Vous devez maintenant adapter les controllers et vues"
echo ""
echo "🔧 Prochaines étapes:"
echo "   1. Tester l'authentification"
echo "   2. Adapter UserController pour gérer les champs de membre"
echo "   3. Mettre à jour les vues pour afficher les données complètes"
echo "   4. Supprimer progressivement les références à 'membres'"
