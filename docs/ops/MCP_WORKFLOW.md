# MCP Workflow — Plan → Diff → Patch → Tests → Rollback

1) **Plan** : l’IA propose un plan (fichiers, migrations, risques, rollback).
2) **Backup** (MCP) : snapshot DB + fichiers.
3) **Diff** : visualisation des changements.
4) **Patch** : apply en here-docs (edit-in-place).
5) **Post-checks** : santé, routes, policies, UI.
6) **Tests** : Pest + e2e si présent.
7) **Rollback** : en cas d’échec (migrations/code/UI/données).
