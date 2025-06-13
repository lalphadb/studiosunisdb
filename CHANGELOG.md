# StudiosUnisDB - Changelog

## Version 3.6.0.0 - Module Cours + Sécurité Multi-École (2025-06-13)

### ✨ Nouvelles fonctionnalités
- **Module Cours complet** : CRUD, validation, interface moderne
- **Sécurité multi-école** : Restrictions granulaires par rôle
- **22 écoles Studios Unis** : Couverture complète du Québec
- **Navigation adaptative** : Menus selon permissions utilisateur

### 🔒 Système de sécurité
- **SuperAdmin** : Accès global (toutes écoles, tous modules)
- **Admin d'école** : Accès restreint à son école uniquement
- **Instructeur** : Accès restreint à son école uniquement
- **Membre** : Accès consultation profil (à développer)

### 🏢 Écoles du réseau (22 total)
- Région Montréal : 6 écoles
- Région Québec : 4 écoles  
- Région Gatineau : 3 écoles
- Autres régions : 9 écoles

### 📊 Modules opérationnels (8/10)
✅ Authentication (Laravel Breeze + Spatie Permission)
✅ Dashboard (métriques temps réel)
✅ Gestion Écoles (SuperAdmin only)
✅ Gestion Membres (avec export Excel)
✅ Gestion Cours (NOUVEAU - complet)
✅ Pages légales (conformité Loi 25)
✅ Interface UI/UX (design cohérent)
✅ Audit trail (ActivityLog)
🔄 Gestion Présences (en développement)
🔄 Gestion Ceintures (en développement)

### 🎯 Prochaines étapes v3.7.0.0
- Module Présences (prise présence QR codes)
- Module Ceintures (évaluations + certificats)
- API REST pour mobile
- Rapports avancés

---

## Versions précédentes

### v3.5.7.8 - Stabilisation interface
- Correction erreurs 500
- Layout admin uniforme
- Navigation cohérente

### v3.5.0.0 - Base fonctionnelle
- Authentication complète
- Modules Écoles et Membres
- Conformité légale Québec
