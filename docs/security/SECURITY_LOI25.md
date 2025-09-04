# Sécurité & Loi 25 — StudiosDB

- **Mono-école** : scoping `ecole_id` partout (Policies/Queries/Resources).
- **Permissions** : Spatie roles/permissions (`superadmin`, `admin_ecole`, `instructeur`, `membre`).
- **Consentements** : table versionnée (type, version, consenti_at, métadonnées).  
  - Exportabilité, effacement, traçabilité (journal consultations/CRUD).
- **Logs** : connexions, accès données sensibles, changements de rôles.
- **Données sensibles** : minimisation, chiffrement si applicable.
- **Rate-limit** : throttling et protections CSRF.
- **Preuve** : rapport d’audit joint à chaque CRQ.
