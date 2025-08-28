# Scripts Sauvegarde StudiosDB

## 🚀 Utilisation Rapide

### Sauvegarde Complète (Recommandé)
```bash
./BACKUP.sh
```
**Sauvegarde intégrale** : Code + DB + Documentation + Commit git

### État Projet
```bash
./STATUS.sh      # État rapide  
./HELP.sh        # Liste tous les scripts disponibles
```

### Tests Interface  
```bash
php artisan serve --port=8001
# → http://127.0.0.1:8001
```

---

## 📋 Scripts Disponibles

| Script | Description | Usage |
|--------|-------------|-------|
| `BACKUP.sh` | **Sauvegarde complète projet** | `./BACKUP.sh` |
| `STATUS.sh` | État rapide projet | `./STATUS.sh` |
| `HELP.sh` | Aide & liste scripts | `./HELP.sh` |
| `CHECK_BEFORE_BACKUP.sh` | Vérifications prérequis | `./CHECK_BEFORE_BACKUP.sh` |

### Sauvegardes Spécialisées
| Script | Description |
|--------|-------------|
| `SAUVEGARDE_COMPLETE_PROJET.sh` | Script principal sauvegarde |
| `BACKUP_PROJECT_COMPLETE.sh` | Code seulement (sans DB) |
| `BACKUP_DATABASE.sh` | Base de données seulement |

### Module Cours
| Script | Description |
|--------|-------------|
| `FIX_COMPLET_COURS.sh` | Corrections contraintes DB |
| `TEST_SIMULATION.sh` | Tests module Cours |
| `BACKUP_COURS.sh` | Sauvegarde module uniquement |

---

## 📊 État Projet StudiosDB

**Modules Terminés** : 3/6
- ✅ Bootstrap sécurité (roles/policies)  
- ✅ Dashboard (UI référence)
- ✅ Cours (fonctionnel référence) - **Contraintes DB résolues**

**Prochaine Étape** : Module Utilisateurs (J4)

**Stack** : Laravel 12.x + Inertia + Vue 3 + Tailwind + MySQL

---

## 🎯 Actions Courantes

### 1. Sauvegarder Avant Nouveau Développement
```bash
./BACKUP.sh
```

### 2. Vérifier État Après Modifications  
```bash
./STATUS.sh
```

### 3. Tester Interface Utilisateur
```bash
php artisan serve --port=8001
```

### 4. Voir Aide Complète
```bash
./HELP.sh
```

---

## 📁 Structure Sauvegarde

Après `./BACKUP.sh`, trouve la sauvegarde dans :
```
backups/studiosdb_full_YYYYMMDD_HH-MM-SS/
├── app/                    # Controllers, Models, Policies
├── database/              # Migrations, seeders
├── resources/             # Pages Vue, components
├── database_dumps/        # Exports DB
├── project_docs/          # Documentation
├── utility_scripts/       # Scripts utilitaires  
└── system_state/          # État système
```

---

## 🚨 Dépannage

### Script non exécutable
```bash
chmod +x *.sh
```

### Erreur permissions
```bash
sudo chown -R $USER:$USER .
```

### Base de données inaccessible
```bash
# Vérifier .env
cat .env | grep DB_

# Tester connexion
php artisan tinker --execute="DB::select('SELECT 1');"
```

---

## 📞 Support

1. **Aide générale** : `./HELP.sh`
2. **État détaillé** : `cat README_STATUS.md`  
3. **Documentation technique** : `docs/ADR-*.md`
4. **Scripts auto-documentés** : Chaque script inclut ses instructions

---

*Scripts générés automatiquement pour StudiosDB - 2025*
