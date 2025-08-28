# ADR-20250828-COURS-CONTRAINTES-DB

## Contexte
Module Cours avec erreurs contraintes base de données empêchant création de cours non-mensuels :
- `SQLSTATE[23000]: Integrity constraint violation: 1048 Column 'tarif_mensuel' cannot be null`
- `SQLSTATE[HY000]: General error: 1364 Field 'ecole_id' doesn't have a default value`

## Décision
Implémentation solution robuste en 3 couches :

### 1. Base de données
- **Migration `tarif_mensuel` nullable** : Colonne peut accepter NULL pour cours non-mensuels
- **Migration `ecole_id` default** : Valeur par défaut pour environnement mono-école

### 2. Validation (FormRequests Laravel 12)
- **StoreCoursRequest** : Validation centralisée avec `prepareForValidation()`
- **UpdateCoursRequest** : Idem pour modifications
- **Fallback robuste** : Auto-assignation `ecole_id` avec fallbacks multiples

### 3. Interface utilisateur
- **Formulaires Vue** : Initialisation `tarif_mensuel: null` au lieu de string vide
- **Logique submit** : Gestion conditionnelle selon `type_tarif`

## Alternatives considérées

### Alternative 1: Migration complète vers nouveau système
- **Avantage** : Plus propre architecturalement
- **Inconvénient** : Risque régression, perte compatibilité
- **Rejet** : Viole principe "pas de reset global"

### Alternative 2: Patches contrôleur uniquement  
- **Avantage** : Changement minimal
- **Inconvénient** : Ne respecte pas standards Laravel 12, validation dispersée
- **Rejet** : Pas maintenable long terme

### Alternative 3: Solution choisie (hybride)
- **Avantage** : Robuste, respecte Laravel 12, rétrocompatible, fallbacks
- **Inconvénient** : Plus de fichiers à maintenir
- **Sélection** : Équilibre optimal robustesse/maintenabilité

## Conséquences

### Positives
✅ **Robustesse** : Multiple fallbacks ecole_id, gestion null tarif_mensuel  
✅ **Standards** : FormRequests Laravel 12, validation centralisée  
✅ **Rétrocompatibilité** : Cours mensuels fonctionnent toujours  
✅ **Maintenabilité** : Code organisé, messages d'erreur français  
✅ **Testabilité** : Validation isolée dans FormRequests  

### Négatives  
⚠️ **Complexité** : Plus de fichiers (2 FormRequests + 2 migrations)  
⚠️ **Dépendance** : Fallback ecole_id=1 pour mono-école  
⚠️ **Migration** : Changements structure DB (réversibles)

### Risques atténués
🛡️ **Migration rollback** : `php artisan migrate:rollback --step=2`  
🛡️ **Fallback multiple** : user.ecole_id → table ecoles → ID=1  
🛡️ **Validation stricte** : Messages erreur explicites français  

## Plan de migration
1. **Phase 1** : Application migrations DB (nullable + default)
2. **Phase 2** : Déploiement FormRequests + contrôleur modifié  
3. **Phase 3** : Mise à jour formulaires Vue
4. **Phase 4** : Tests validation complète
5. **Phase 5** : Monitoring production

## Métriques de succès
- [x] Création cours MENSUEL : Aucune régression
- [x] Création cours TRIMESTRIEL/HORAIRE/A_LA_CARTE : Fonctionnel
- [x] Validation erreurs : Messages français contextuels
- [x] Performance : Aucun impact négatif
- [x] Rollback : Procédure testée et fonctionnelle

## Références
- Laravel 12 FormRequests : https://laravel.com/docs/12.x/validation#form-request-validation
- Migration Schema Builder : https://laravel.com/docs/12.x/migrations#modifying-columns
- Vue 3 Composition API : https://vuejs.org/guide/extras/composition-api-faq.html

## Révisions
- **v1.0** : Décision initiale (2025-08-28)
- **Status** : IMPLÉMENTÉ et VALIDÉ

---
*ADR maintenu par Lead Engineer - StudiosDB Team*
