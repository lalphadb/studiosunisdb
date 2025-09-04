# Gouvernance du changement (CRQ) — StudiosDB

## États des modules
- `ACTIVE` : modifiable (via CRQ approuvée)
- `ISSUE` : problème ouvert (correctifs autorisés)
- `REVIEW` : en validation comité
- `APPROVED` : validé, prêt à livrer
- `IMPLEMENTED` : livré (tests OK)
- `FROZEN` : gelé (intouchable sans CRQ)

## Règles
- Toute modification passe par une **CRQ** (template `/docs/prompts/20_crq_template.md`).
- Aucune modification des modules **[FROZEN]** sans CRQ approuvée.
- **Rollback** obligatoire documenté (migrations/code/UI/données).

## Process CRQ
1. CRQ remplie → `REVIEW`
2. Analyses sécurité/loi25, UI, tests → vote comité
3. Si `APPROVED` : plan→diff→patch (edit-in-place)
4. Backup (MCP) → Apply → Post-checks → Tests
5. `IMPLEMENTED` puis (optionnel) `FROZEN`

## Journal
- Conserver la CRQ en `/docs/governance/crq/YYYY-MM-DD_<nom>.md`
- Lier commit(s), rapports tests, audits, captures d’écran.
