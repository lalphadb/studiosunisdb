# Tests & Definition of Done (DoD)

## DoD par module
- Migrations stables (rollback OK)
- Policies + tests d’accès (rôles + `ecole_id`)
- Tests de flux critiques (Pest)
- A11y de base
- **Zéro régression Cours**

## Jeux de tests minimaux
- Accès par rôle (superadmin/admin_ecole/instructeur/membre)
- Conflits horaires (instructeur & membre)
- Promotions ceinture (manuelles)
- Exports PDF/Excel générés
- Inscription autonome et détection de conflits
