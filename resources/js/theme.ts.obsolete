// StudiosDB - Configuration du thème sombre slate/indigo/purple
// Ce fichier centralise les classes du design system

export const theme = {
  // Backgrounds
  backgrounds: {
    gradient: 'bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950',
    panel: 'bg-slate-900/60',
    card: 'bg-slate-900/50',
    input: 'bg-slate-800/60',
    hover: 'hover:bg-slate-800/50',
    modal: 'bg-slate-900/95'
  },
  
  // Borders
  borders: {
    default: 'border border-slate-700/50',
    input: 'border border-slate-700',
    focus: 'focus:ring-2 focus:ring-indigo-500 focus:border-transparent',
    card: 'border border-slate-700/50'
  },
  
  // Text colors
  text: {
    primary: 'text-white',
    secondary: 'text-slate-300',
    muted: 'text-slate-400',
    label: 'text-slate-400',
    accent: 'text-indigo-300',
    purple: 'text-purple-400',
    error: 'text-red-400'
  },
  
  // Buttons
  buttons: {
    primary: 'bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-white',
    secondary: 'bg-slate-700 hover:bg-slate-600 text-slate-200',
    danger: 'bg-red-600/80 hover:bg-red-600 text-white',
    ghost: 'bg-slate-800/70 hover:bg-slate-700 text-slate-300'
  },
  
  // Spacing & Layout
  layout: {
    containerPadding: 'p-6',
    cardPadding: 'p-6',
    cardRadius: 'rounded-2xl',
    buttonRadius: 'rounded-lg',
    inputRadius: 'rounded-lg',
    maxWidth: 'max-w-7xl mx-auto'
  },
  
  // Components classes
  components: {
    statCard: 'rounded-2xl border border-slate-700/50 bg-slate-900/60 p-6',
    pageHeader: 'mb-10',
    formField: 'w-full rounded-lg bg-slate-800/60 border border-slate-700 text-slate-100 px-3 py-2',
    badge: {
      success: 'bg-emerald-500/20 text-emerald-400',
      warning: 'bg-amber-500/20 text-amber-400',
      danger: 'bg-red-500/20 text-red-400',
      info: 'bg-blue-500/20 text-blue-400'
    }
  }
}

// Helper function pour combiner les classes
export function cn(...classes: string[]): string {
  return classes.filter(Boolean).join(' ')
}

// Export des classes complètes pour les composants
export const fieldClasses = cn(
  theme.components.formField,
  theme.borders.focus,
  'transition placeholder-slate-500'
)

export const cardClasses = cn(
  theme.components.statCard,
  'backdrop-blur-xl'
)

export const buttonPrimaryClasses = cn(
  theme.buttons.primary,
  theme.layout.buttonRadius,
  'px-4 py-2 font-medium transition shadow'
)
