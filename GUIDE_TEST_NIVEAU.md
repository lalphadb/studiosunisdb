# Guide de Test - Correction ENUM niveau

## 🚨 PROBLÈME ACTUEL
Erreur SQL: `Data truncated for column 'niveau' at row 1`

**Cause:** Colonne `niveau` définie comme ENUM restrictif, ne supporte pas les nouveaux niveaux.

## 🔧 SOLUTION IMMÉDIATE

```bash
cd /home/studiosdb/studiosunisdb

# Option A: Script automatique
chmod +x fix-niveau-immediate.sh
./fix-niveau-immediate.sh

# Option B: Manuel
php artisan migrate --force
```

## ✅ TESTS POST-CORRECTION

### 1. Vérification base de données
```bash
php artisan tinker --execute="
\$result = DB::select('SHOW COLUMNS FROM cours WHERE Field = \"niveau\"');
echo 'Type colonne niveau: ' . \$result[0]->Type;
"
```

**Résultat attendu:** 
```
enum('tous','debutant','intermediaire','avance','prive','competition','a_la_carte')
```

### 2. Test formulaire création cours

1. **Rafraîchir** la page `/cours/create`
2. **Vérifier** les options niveau disponibles :
   - ✅ Tous
   - ✅ Débutant  
   - ✅ Intermédiaire
   - ✅ Avancé
   - ✅ Privé
   - ✅ Compétition
   - ✅ À la carte

### 3. Test création cours complet

**Données de test:**
- Nom: `Test Karaté Flexible`
- Niveau: `À la carte` (nouveau niveau)
- Âge min: `8`
- Âge max: `Laisser vide` (optionnel)
- Places max: `15`
- Jour: `Samedi`
- Heure début: `10:00`
- Heure fin: `11:30`
- Type tarif: `À la carte (10 samedis)`
- Montant: `150.00`

### 4. Validation tarification flexible

**Tester tous les types de tarif:**
- ✅ Mensuel → Montant simple
- ✅ Trimestriel → Montant pour 3 mois
- ✅ Horaire → Prix par séance
- ✅ À la carte → Prix pour 10 samedis
- ✅ Autre → Champ détails requis

## 🚨 SI ERREURS PERSISTENT

### Erreur "Call to undefined method"
```bash
composer dump-autoload
php artisan config:clear
php artisan optimize:clear
```

### Erreur permissions 403
```bash
# Vérifier diagnostic
curl http://127.0.0.1:8001/debug/cours-access
```

### Problème utilisateur admin
```bash
./auto-fix-v2.sh
```

## ✅ SUCCÈS ATTENDU

Après correction, vous devez pouvoir:
1. ✅ Créer un cours avec niveau "À la carte"
2. ✅ Utiliser la tarification flexible
3. ✅ Âge maximum optionnel (champ vide accepté)
4. ✅ Voir le cours dans la liste `/cours`
5. ✅ Éditer le cours créé

## 📞 SUPPORT

Si problèmes persistent:
- Logs: `storage/logs/laravel.log`
- Console navigateur: F12 → Console
- Route debug: `/debug/cours-access`
