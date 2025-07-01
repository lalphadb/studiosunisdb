# 🎨 SYSTÈME DE COULEURS STUDIOSDB v4.1.10.2

## Philosophie Design
- **Professionnalisme**: Couleurs sobres et élégantes
- **Confort visuel**: Opacités réduites (10-25%) pour éviter la fatigue oculaire
- **Cohérence**: Chaque module a sa couleur mais avec harmonisation globale
- **Accessibilité**: Contraste suffisant, lisibilité optimale

## Palette Principale (Opacités Douces)

### 🔷 BLEU (Users/Dashboard)
- Primary: `from-blue-500/15 via-blue-600/20 to-cyan-500/15`
- Hover: `hover:from-blue-500/25 hover:via-blue-600/30 hover:to-cyan-500/25`
- Active: `from-blue-500/30 via-blue-600/35 to-cyan-500/30`

### 🟢 VERT (Écoles)
- Primary: `from-green-500/15 via-green-600/20 to-emerald-500/15`
- Hover: `hover:from-green-500/25 hover:via-green-600/30 hover:to-emerald-500/25`
- Active: `from-green-500/30 via-green-600/35 to-emerald-500/30`

### 🟣 VIOLET (Cours)
- Primary: `from-purple-500/15 via-purple-600/20 to-indigo-500/15`
- Hover: `hover:from-purple-500/25 hover:via-purple-600/30 hover:to-indigo-500/25`
- Active: `from-purple-500/30 via-purple-600/35 to-indigo-500/30`

### 🟡 JAUNE (Paiements)
- Primary: `from-yellow-500/15 via-yellow-600/20 to-orange-500/15`
- Hover: `hover:from-yellow-500/25 hover:via-yellow-600/30 hover:to-orange-500/25`
- Active: `from-yellow-500/30 via-yellow-600/35 to-orange-500/30`

### 🔶 ORANGE (Ceintures)
- Primary: `from-orange-500/15 via-orange-600/20 to-red-500/15`
- Hover: `hover:from-orange-500/25 hover:via-orange-600/30 hover:to-red-500/25`
- Active: `from-orange-500/30 via-orange-600/35 to-red-500/30`

### 🔵 CYAN (Présences)
- Primary: `from-teal-500/15 via-teal-600/20 to-green-500/15`
- Hover: `hover:from-teal-500/25 hover:via-teal-600/30 hover:to-green-500/25`
- Active: `from-teal-500/30 via-teal-600/35 to-green-500/30`

### 🩷 ROSE (Séminaires)
- Primary: `from-pink-500/15 via-pink-600/20 to-purple-500/15`
- Hover: `hover:from-pink-500/25 hover:via-pink-600/30 hover:to-purple-500/25`
- Active: `from-pink-500/30 via-pink-600/35 to-purple-500/30`

## Couleurs Neutres (Base)
- **Background**: `bg-slate-900` (principal), `bg-slate-800` (cards)
- **Borders**: `border-slate-700/20`, `border-slate-600/30`
- **Text**: `text-slate-100` (principal), `text-slate-300` (secondaire), `text-slate-400` (tertiaire)
- **Overlays**: `bg-slate-900/60`, `bg-black/20`

## États et Feedback
- **Success**: `from-green-500/20 via-emerald-500/25 to-green-400/20`
- **Warning**: `from-yellow-500/20 via-orange-500/25 to-yellow-400/20`
- **Error**: `from-red-500/20 via-red-600/25 to-red-400/20`
- **Info**: `from-blue-500/20 via-cyan-500/25 to-blue-400/20`

## Règles d'Application
1. **Headers principaux**: Toujours via x-module-header
2. **Sections intérieures**: Maximum /25 d'opacité
3. **Boutons**: /20 normal, /30 hover, /35 active
4. **Cards/Métriques**: `bg-slate-800/30` avec bordures `/20`
5. **Overlays**: Jamais plus de /60
