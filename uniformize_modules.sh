#!/bin/bash

# ========================================
# SCRIPT D'UNIFORMISATION DES MODULES
# StudiosDB v5 - Laravel 12.21 + Vue 3
# ========================================

set -e  # Arrêter en cas d'erreur

# Couleurs pour output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Répertoire de base
BASE_DIR="/home/studiosdb/studiosunisdb"
COMPONENTS_DIR="$BASE_DIR/resources/js/Components"
PAGES_DIR="$BASE_DIR/resources/js/Pages"

echo -e "${BLUE}╔════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║   UNIFORMISATION DES MODULES STUDIOSDB V5   ║${NC}"
echo -e "${BLUE}╚════════════════════════════════════════════╝${NC}"
echo ""

# Fonction pour créer un composant
create_component() {
    local component_path=$1
    local component_name=$2
    local component_content=$3
    
    echo -e "${YELLOW}→ Création du composant: ${component_name}${NC}"
    
    # Créer le répertoire si nécessaire
    mkdir -p "$(dirname "$component_path")"
    
    # Créer le fichier
    cat > "$component_path" << 'EOF'
$component_content
EOF
    
    if [ -f "$component_path" ]; then
        echo -e "${GREEN}  ✓ ${component_name} créé avec succès${NC}"
    else
        echo -e "${RED}  ✗ Erreur lors de la création de ${component_name}${NC}"
    fi
}

# ========================================
# 1. CRÉATION DES COMPOSANTS LAYOUT
# ========================================

echo -e "\n${BLUE}═══ 1. CRÉATION DES COMPOSANTS LAYOUT ═══${NC}\n"

# PageHeader.vue
cat > "$COMPONENTS_DIR/Layout/PageHeader.vue" << 'EOF'
<template>
  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
    <div class="mb-4 lg:mb-0">
      <div class="flex items-center space-x-3 mb-2">
        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg">
          <component :is="icon" class="h-7 w-7 text-white" />
        </div>
        <div>
          <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-100 to-blue-300 bg-clip-text text-transparent">
            {{ title }}
          </h1>
          <p v-if="subtitle" class="text-blue-200 font-medium">{{ subtitle }}</p>
        </div>
      </div>
    </div>
    <div v-if="$slots.actions" class="flex items-center space-x-3">
      <slot name="actions" />
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  subtitle: String,
  icon: Object
})
</script>
EOF
echo -e "${GREEN}✓ PageHeader.vue créé${NC}"

# StatsGrid.vue
cat > "$COMPONENTS_DIR/Layout/StatsGrid.vue" << 'EOF'
<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 w-full">
    <ModernStatsCard
      v-for="stat in stats"
      :key="stat.key"
      :title="stat.title"
      :value="stat.value"
      :icon-type="stat.iconType"
      :icon-name="stat.iconName"
      :format="stat.format"
      :description="stat.description"
      :trend="stat.trend"
    />
  </div>
</template>

<script setup>
import ModernStatsCard from '@/Components/ModernStatsCard.vue'

defineProps({
  stats: Array
})
</script>
EOF
echo -e "${GREEN}✓ StatsGrid.vue créé${NC}"

# FilterBar.vue
cat > "$COMPONENTS_DIR/Layout/FilterBar.vue" << 'EOF'
<template>
  <div class="bg-blue-900/60 backdrop-blur-xl border border-blue-800/50 rounded-xl p-6 mb-8 shadow-md w-full">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
      <div v-for="filter in filterConfig" :key="filter.key" :class="filter.colSpan || ''">
        <label class="block text-sm font-medium text-blue-200 mb-2">{{ filter.label }}</label>
        
        <input
          v-if="filter.type === 'text'"
          v-model="localFilters[filter.key]"
          @input="handleInput(filter.key)"
          :placeholder="filter.placeholder"
          type="text"
          class="w-full bg-blue-950/60 border border-blue-800 rounded-lg px-3 py-2 text-white placeholder-blue-400 focus:outline-none focus:border-blue-600 transition-colors"
        />
        
        <select
          v-else-if="filter.type === 'select'"
          v-model="localFilters[filter.key]"
          @change="handleChange"
          class="w-full bg-blue-950/60 border border-blue-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-blue-600 transition-colors"
        >
          <option value="">{{ filter.placeholder }}</option>
          <option v-for="option in filter.options" :key="option.value" :value="option.value">
            {{ option.label }}
          </option>
        </select>
      </div>
    </div>
    
    <div v-if="hasActiveFilters" class="mt-4 flex items-center justify-between">
      <div class="flex flex-wrap items-center gap-2">
        <span class="text-sm text-blue-300">Filtres actifs:</span>
        <span
          v-for="(value, key) in activeFilters"
          :key="key"
          class="inline-flex items-center gap-1 px-3 py-1 bg-blue-800/50 text-blue-200 rounded-full text-sm"
        >
          {{ getFilterLabel(key) }}: {{ value }}
          <button @click="removeFilter(key)" class="ml-1 hover:text-white transition-colors">
            <XMarkIcon class="h-4 w-4" />
          </button>
        </span>
      </div>
      <button
        @click="resetFilters"
        class="text-sm text-blue-300 hover:text-white transition-colors underline"
      >
        Réinitialiser
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  filters: Object,
  filterConfig: Array
})

const emit = defineEmits(['update'])

const localFilters = ref({ ...props.filters })

const hasActiveFilters = computed(() => {
  return Object.values(localFilters.value).some(val => val !== '')
})

const activeFilters = computed(() => {
  const active = {}
  Object.entries(localFilters.value).forEach(([key, value]) => {
    if (value !== '') {
      active[key] = value
    }
  })
  return active
})

const handleInput = (key) => {
  if (key === 'search') {
    debounceSearch()
  }
}

const handleChange = () => {
  emit('update', localFilters.value)
}

const debounceSearch = (() => {
  let timeout
  return () => {
    clearTimeout(timeout)
    timeout = setTimeout(() => {
      emit('update', localFilters.value)
    }, 300)
  }
})()

const removeFilter = (key) => {
  localFilters.value[key] = ''
  emit('update', localFilters.value)
}

const resetFilters = () => {
  Object.keys(localFilters.value).forEach(key => {
    localFilters.value[key] = ''
  })
  emit('update', localFilters.value)
}

const getFilterLabel = (key) => {
  const filter = props.filterConfig.find(f => f.key === key)
  return filter ? filter.label : key
}
</script>
EOF
echo -e "${GREEN}✓ FilterBar.vue créé${NC}"

# ========================================
# 2. CRÉATION DES COMPOSANTS FORM
# ========================================

echo -e "\n${BLUE}═══ 2. CRÉATION DES COMPOSANTS FORM ═══${NC}\n"

mkdir -p "$COMPONENTS_DIR/Form"

# InputField.vue
cat > "$COMPONENTS_DIR/Form/InputField.vue" << 'EOF'
<template>
  <div>
    <label v-if="label" :for="id" class="block text-sm font-medium text-blue-200 mb-2">
      {{ label }}
      <span v-if="required" class="text-red-400">*</span>
    </label>
    <input
      :id="id"
      :type="type"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :placeholder="placeholder"
      :required="required"
      :disabled="disabled"
      class="w-full bg-blue-950/60 border border-blue-800 rounded-lg px-4 py-2 text-white placeholder-blue-400 focus:outline-none focus:border-blue-500 transition-colors disabled:opacity-50"
    />
    <p v-if="error" class="mt-1 text-sm text-red-400">{{ error }}</p>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: [String, Number],
  label: String,
  type: {
    type: String,
    default: 'text'
  },
  placeholder: String,
  required: Boolean,
  disabled: Boolean,
  error: String
})

const id = computed(() => `input-${Math.random().toString(36).substr(2, 9)}`)

defineEmits(['update:modelValue'])
</script>
EOF
echo -e "${GREEN}✓ InputField.vue créé${NC}"

# SelectField.vue
cat > "$COMPONENTS_DIR/Form/SelectField.vue" << 'EOF'
<template>
  <div>
    <label v-if="label" :for="id" class="block text-sm font-medium text-blue-200 mb-2">
      {{ label }}
      <span v-if="required" class="text-red-400">*</span>
    </label>
    <select
      :id="id"
      :value="modelValue"
      @change="$emit('update:modelValue', $event.target.value)"
      :required="required"
      :disabled="disabled"
      class="w-full bg-blue-950/60 border border-blue-800 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500 transition-colors disabled:opacity-50"
    >
      <option value="">{{ placeholder || 'Sélectionner...' }}</option>
      <option v-for="option in options" :key="option.value" :value="option.value">
        {{ option.label }}
      </option>
    </select>
    <p v-if="error" class="mt-1 text-sm text-red-400">{{ error }}</p>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: [String, Number],
  label: String,
  options: Array,
  placeholder: String,
  required: Boolean,
  disabled: Boolean,
  error: String
})

const id = computed(() => `select-${Math.random().toString(36).substr(2, 9)}`)

defineEmits(['update:modelValue'])
</script>
EOF
echo -e "${GREEN}✓ SelectField.vue créé${NC}"

# ========================================
# 3. CRÉATION DES COMPOSANTS UI
# ========================================

echo -e "\n${BLUE}═══ 3. CRÉATION DES COMPOSANTS UI ═══${NC}\n"

mkdir -p "$COMPONENTS_DIR/UI"

# ActionButton.vue
cat > "$COMPONENTS_DIR/UI/ActionButton.vue" << 'EOF'
<template>
  <component
    :is="href ? 'Link' : 'button'"
    :href="href"
    @click="!href && $emit('click')"
    :class="buttonClasses"
    :disabled="disabled || loading"
  >
    <component :is="iconComponent" v-if="icon" class="h-5 w-5" />
    <span v-if="loading" class="inline-flex">
      <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
    </span>
    <span v-else>{{ label }}</span>
  </component>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { 
  PlusIcon, 
  ArrowDownTrayIcon,
  PencilIcon,
  TrashIcon,
  EyeIcon 
} from '@heroicons/vue/24/outline'

const props = defineProps({
  type: {
    type: String,
    default: 'primary'
  },
  label: String,
  href: String,
  icon: String,
  disabled: Boolean,
  loading: Boolean,
  size: {
    type: String,
    default: 'md'
  }
})

defineEmits(['click'])

const iconMap = {
  'plus': PlusIcon,
  'download': ArrowDownTrayIcon,
  'edit': PencilIcon,
  'delete': TrashIcon,
  'view': EyeIcon
}

const iconComponent = computed(() => props.icon ? iconMap[props.icon] : null)

const buttonClasses = computed(() => {
  const base = 'inline-flex items-center justify-center space-x-2 rounded-lg font-medium transition-all duration-200 disabled:opacity-50'
  
  const sizes = {
    sm: 'px-3 py-1.5 text-sm',
    md: 'px-5 py-2.5 text-base',
    lg: 'px-6 py-3 text-lg'
  }
  
  const types = {
    primary: 'bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white shadow-md',
    secondary: 'bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white shadow-md',
    danger: 'bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white shadow-md',
    ghost: 'bg-transparent hover:bg-blue-800/30 text-blue-300 hover:text-white'
  }
  
  return `${base} ${sizes[props.size]} ${types[props.type]}`
})
</script>
EOF
echo -e "${GREEN}✓ ActionButton.vue créé${NC}"

# Badge.vue
cat > "$COMPONENTS_DIR/UI/Badge.vue" << 'EOF'
<template>
  <span :class="badgeClasses">
    {{ label }}
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  label: String,
  type: {
    type: String,
    default: 'default'
  },
  size: {
    type: String,
    default: 'md'
  }
})

const badgeClasses = computed(() => {
  const base = 'inline-flex items-center font-medium rounded-full border'
  
  const sizes = {
    sm: 'px-2 py-0.5 text-xs',
    md: 'px-3 py-1 text-sm',
    lg: 'px-4 py-1.5 text-base'
  }
  
  const types = {
    default: 'bg-blue-900/50 text-blue-300 border-blue-700',
    success: 'bg-green-900/50 text-green-300 border-green-700',
    warning: 'bg-yellow-900/50 text-yellow-300 border-yellow-700',
    danger: 'bg-red-900/50 text-red-300 border-red-700',
    info: 'bg-indigo-900/50 text-indigo-300 border-indigo-700'
  }
  
  return `${base} ${sizes[props.size]} ${types[props.type]}`
})
</script>
EOF
echo -e "${GREEN}✓ Badge.vue créé${NC}"

# ========================================
# 4. VÉRIFICATION ET COMPILATION
# ========================================

echo -e "\n${BLUE}═══ 4. VÉRIFICATION ET COMPILATION ═══${NC}\n"

cd "$BASE_DIR"

# Vérifier si npm est installé
if command -v npm &> /dev/null; then
    echo -e "${YELLOW}→ Compilation des assets...${NC}"
    npm run build
    echo -e "${GREEN}✓ Assets compilés avec succès${NC}"
else
    echo -e "${RED}✗ npm n'est pas installé${NC}"
fi

# Vérifier les migrations
echo -e "\n${YELLOW}→ Vérification des migrations...${NC}"
php artisan migrate:status | head -20

# Clear caches
echo -e "\n${YELLOW}→ Nettoyage des caches...${NC}"
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo -e "\n${GREEN}╔════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║     UNIFORMISATION TERMINÉE AVEC SUCCÈS     ║${NC}"
echo -e "${GREEN}╚════════════════════════════════════════════╝${NC}"

echo -e "\n${BLUE}Prochaines étapes:${NC}"
echo -e "1. Tester les nouveaux composants"
echo -e "2. Mettre à jour les pages existantes"
echo -e "3. Créer les pages manquantes"
echo -e "4. Tester l'application complète"

echo -e "\n${YELLOW}Documentation disponible dans:${NC}"
echo -e "→ $BASE_DIR/PLAN_UNIFORMISATION_MODULES.md"
