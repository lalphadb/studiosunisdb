# 🎨 Thème Sombre Slate/Indigo/Purple - StudiosDB

## 📍 Où se trouve le thème actuellement ?

Le thème n'était **PAS** dans un fichier centralisé. Il était **dispersé** dans chaque composant Vue via des classes Tailwind.

### Fichiers qui utilisent le thème :

| Fichier | Statut | Classes principales |
|---------|---------|-------------------|
| **resources/js/Pages/Welcome.vue** | ✅ Référence du thème | `from-slate-950 via-slate-900 to-slate-950` |
| **resources/js/Pages/Dashboard/Admin.vue** | ✅ Thème complet | `bg-slate-900/60`, `border-slate-700/50` |
| **resources/js/Pages/Auth/Login.vue** | ✅ Uniformisé | `bg-slate-800/50`, `from-blue-400 to-purple-400` |
| **resources/js/Pages/Auth/LoginTurnstile.vue** | ✅ Nouveau avec thème | Même palette |
| **resources/js/Pages/Membres/Create.vue** | ✅ Thème appliqué | `bg-slate-900/60`, panneaux unifiés |
| **resources/js/Pages/Membres/Edit.vue** | ✅ Thème appliqué | Idem Create |
| **resources/js/Pages/Membres/Index.vue** | ⚠️ Partiellement | Header migré, reste à faire |
| **resources/js/Pages/Membres/Show.vue** | ⚠️ Partiellement | Mélange ancien/nouveau |

## 🆕 Nouveau : Fichier de configuration centralisé

J'ai créé **`resources/js/theme.ts`** pour centraliser le thème :

```typescript
// Utilisation dans vos composants Vue
import { theme, fieldClasses, cardClasses } from '@/theme'

// Dans le template
<div :class="theme.backgrounds.gradient">
<button :class="theme.buttons.primary">
```

## 🎯 Classes du thème sombre

### Palette principale
```css
/* Backgrounds */
bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950  /* Fond principal */
bg-slate-900/60  /* Panneaux */
bg-slate-800/70  /* Éléments secondaires */
bg-slate-700/50  /* Hover states */

/* Borders */
border-slate-700/50  /* Bordures subtiles */
border-slate-600     /* Bordures inputs */

/* Text */
text-white          /* Texte principal */
text-slate-300      /* Texte secondaire */
text-slate-400      /* Labels, muted */

/* Accents */
from-indigo-500 to-purple-600  /* Dégradés primaires */
text-indigo-300                /* Icônes accent */
text-purple-400                /* Liens, highlights */
from-blue-400 to-purple-400    /* Titres dégradés */
```

## 📝 Pour appliquer le thème à une nouvelle page :

### Option 1 : Copier depuis Welcome.vue (référence)
```bash
# Welcome.vue contient le thème complet de référence
cat resources/js/Pages/Welcome.vue | grep -E "bg-|text-|border-|from-|to-"
```

### Option 2 : Utiliser le nouveau theme.ts
```vue
<script setup>
import { theme } from '@/theme'
</script>

<template>
  <div :class="theme.backgrounds.gradient">
    <!-- Votre contenu -->
  </div>
</template>
```

## 🔄 État de la migration

| Module | Uniformisé | À faire |
|--------|------------|---------|
| **Welcome** | ✅ 100% | - |
| **Dashboard** | ✅ 100% | - |
| **Auth** | ✅ 100% | - |
| **Membres** | 🔶 70% | Show.vue à finir |
| **Cours** | ❌ 0% | Tout |
| **Ceintures** | ❌ 0% | Tout |
| **Paiements** | 🔶 30% | Index partiel |
| **Presences** | ❌ 0% | Tout |

## 💡 Recommandation

Utilisez maintenant **`resources/js/theme.ts`** pour tous les nouveaux composants. Cela garantit la cohérence et facilite les modifications futures du thème.

---

**Le thème est donc dans les fichiers Vue individuels, mais j'ai créé `theme.ts` pour le centraliser.**
