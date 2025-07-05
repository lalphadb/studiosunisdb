{{-- 
    Flash Messages Component - StudiosDB
    Compatible Laravel 12 - Standard Professionnel
    Gestion complète des messages flash avec animations
--}}
@props([
    'timeout' => 5000,
    'position' => 'top',
    'dismissible' => true
])

<div class="studiosdb-flash-messages-container" x-data="flashMessages()">
    {{-- Messages Flash Avancés du BaseAdminController --}}
    @if(session('flash_message'))
        @php
            $message = session('flash_message');
            $type = $message['type'] ?? 'info';
            $title = $message['title'] ?? '';
            $content = $message['message'] ?? '';
            $options = $message['options'] ?? [];
            
            $typeClasses = [
                'success' => 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-900/20 dark:border-emerald-700 dark:text-emerald-300',
                'error' => 'bg-red-50 border-red-200 text-red-800 dark:bg-red-900/20 dark:border-red-700 dark:text-red-300',
                'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800 dark:bg-yellow-900/20 dark:border-yellow-700 dark:text-yellow-300',
                'info' => 'bg-blue-50 border-blue-200 text-blue-800 dark:bg-blue-900/20 dark:border-blue-700 dark:text-blue-300',
            ];
            
            $iconClasses = [
                'success' => 'fa-check-circle text-emerald-500',
                'error' => 'fa-exclamation-circle text-red-500',
                'warning' => 'fa-exclamation-triangle text-yellow-500',
                'info' => 'fa-info-circle text-blue-500',
            ];
            
            $actualTimeout = $options['timeout'] ?? $timeout;
            $isDismissible = $options['dismissible'] ?? $dismissible;
        @endphp

        <div x-data="{ show: true }" 
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2 scale-95"
             x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 transform translate-y-2 scale-95"
             class="studiosdb-flash-message studiosdb-flash-{{ $type }} {{ $typeClasses[$type] ?? $typeClasses['info'] }} border rounded-lg p-4 mb-6 shadow-lg backdrop-blur-sm"
             @if($actualTimeout > 0)
             x-init="setTimeout(() => show = false, {{ $actualTimeout }})"
             @endif
             role="alert"
             aria-live="polite"
        >
            <div class="flex items-start">
                <!-- Icône avec animation -->
                <div class="flex-shrink-0 mr-3">
                    <i class="fas {{ $iconClasses[$type] ?? $iconClasses['info'] }} text-lg animate-pulse"></i>
                </div>
                
                <!-- Contenu du message -->
                <div class="flex-grow">
                    @if($title)
                        <h4 class="font-semibold text-sm mb-1 studiosdb-text-primary">{{ $title }}</h4>
                    @endif
                    
                    <div class="text-sm leading-relaxed">
                        @if(is_array($content))
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($content as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        @else
                            {{ $content }}
                        @endif
                    </div>
                </div>
                
                <!-- Bouton de fermeture -->
                @if($isDismissible)
                    <button @click="show = false" 
                            class="flex-shrink-0 ml-3 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-transparent focus:ring-gray-500 rounded"
                            aria-label="Fermer le message">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                @endif
            </div>
        </div>
    @endif

    {{-- Messages Session Standard Laravel --}}
    @if(session('success'))
        <div x-data="{ show: true }" 
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2 scale-95"
             x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 transform translate-y-2 scale-95"
             class="bg-emerald-50 border border-emerald-200 text-emerald-800 dark:bg-emerald-900/20 dark:border-emerald-700 dark:text-emerald-300 rounded-lg p-4 mb-6 shadow-lg"
             x-init="setTimeout(() => show = false, {{ $timeout }})"
             role="alert"
             aria-live="polite"
        >
            <div class="flex items-center">
                <i class="fas fa-check-circle text-emerald-500 mr-3 animate-pulse"></i>
                <span class="text-sm font-medium flex-grow">{{ session('success') }}</span>
                @if($dismissible)
                    <button @click="show = false" 
                            class="ml-3 text-emerald-400 hover:text-emerald-600 dark:text-emerald-500 dark:hover:text-emerald-300 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                @endif
            </div>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" 
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2 scale-95"
             x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
             class="bg-red-50 border border-red-200 text-red-800 dark:bg-red-900/20 dark:border-red-700 dark:text-red-300 rounded-lg p-4 mb-6 shadow-lg"
             role="alert"
             aria-live="assertive"
        >
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <span class="text-sm font-medium flex-grow">{{ session('error') }}</span>
                @if($dismissible)
                    <button @click="show = false" 
                            class="ml-3 text-red-400 hover:text-red-600 dark:text-red-500 dark:hover:text-red-300 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 rounded">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                @endif
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div x-data="{ show: true }" 
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2 scale-95"
             x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
             class="bg-yellow-50 border border-yellow-200 text-yellow-800 dark:bg-yellow-900/20 dark:border-yellow-700 dark:text-yellow-300 rounded-lg p-4 mb-6 shadow-lg"
             x-init="setTimeout(() => show = false, 8000)"
             role="alert"
             aria-live="polite"
        >
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 mr-3"></i>
                <span class="text-sm font-medium flex-grow">{{ session('warning') }}</span>
                @if($dismissible)
                    <button @click="show = false" 
                            class="ml-3 text-yellow-400 hover:text-yellow-600 dark:text-yellow-500 dark:hover:text-yellow-300 transition-colors focus:outline-none focus:ring-2 focus:ring-yellow-500 rounded">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                @endif
            </div>
        </div>
    @endif

    @if(session('info'))
        <div x-data="{ show: true }" 
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2 scale-95"
             x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
             class="bg-blue-50 border border-blue-200 text-blue-800 dark:bg-blue-900/20 dark:border-blue-700 dark:text-blue-300 rounded-lg p-4 mb-6 shadow-lg"
             x-init="setTimeout(() => show = false, 8000)"
             role="alert"
             aria-live="polite"
        >
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                <span class="text-sm font-medium flex-grow">{{ session('info') }}</span>
                @if($dismissible)
                    <button @click="show = false" 
                            class="ml-3 text-blue-400 hover:text-blue-600 dark:text-blue-500 dark:hover:text-blue-300 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 rounded">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                @endif
            </div>
        </div>
    @endif

    @if(session('status'))
        <div x-data="{ show: true }" 
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2 scale-95"
             x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
             class="bg-blue-50 border border-blue-200 text-blue-800 dark:bg-blue-900/20 dark:border-blue-700 dark:text-blue-300 rounded-lg p-4 mb-6 shadow-lg"
             x-init="setTimeout(() => show = false, 6000)"
             role="alert"
             aria-live="polite"
        >
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                <span class="text-sm font-medium flex-grow">{{ session('status') }}</span>
                @if($dismissible)
                    <button @click="show = false" 
                            class="ml-3 text-blue-400 hover:text-blue-600 dark:text-blue-500 dark:hover:text-blue-300 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 rounded">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                @endif
            </div>
        </div>
    @endif

    {{-- Erreurs de Validation --}}
    @if($errors->any())
        <div x-data="{ show: true }" 
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2 scale-95"
             x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
             class="bg-red-50 border border-red-200 text-red-800 dark:bg-red-900/20 dark:border-red-700 dark:text-red-300 rounded-lg p-4 mb-6 shadow-lg"
             role="alert"
             aria-live="assertive"
        >
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle text-red-500 mr-3 mt-0.5"></i>
                <div class="flex-grow">
                    <h4 class="font-semibold text-sm mb-2 studiosdb-text-primary">
                        {{ $errors->count() === 1 ? 'Erreur de validation :' : 'Erreurs de validation :' }}
                    </h4>
                    <ul class="text-sm space-y-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>{{ $error }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @if($dismissible)
                    <button @click="show = false" 
                            class="flex-shrink-0 ml-3 text-red-400 hover:text-red-600 dark:text-red-500 dark:hover:text-red-300 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 rounded">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>

<script>
    function flashMessages() {
        return {
            // Méthodes pour gestion dynamique des messages
            addMessage(type, message, title = '', timeout = 5000) {
                // Logique pour ajouter des messages dynamiquement
                console.log(`Flash message added: ${type} - ${message}`);
            },
            
            clearAll() {
                // Masquer tous les messages
                this.$el.querySelectorAll('[x-data*="show"]').forEach(el => {
                    el.__x.$data.show = false;
                });
            }
        }
    }
</script>

<style>
    /* Styles spécifiques au composant flash-messages */
    .studiosdb-flash-messages-container {
        position: relative;
        z-index: 50;
    }
    
    .studiosdb-flash-message {
        transform-origin: top center;
    }
    
    /* Animation personnalisée pour les icônes */
    .studiosdb-flash-message i.animate-pulse {
        animation: flash-icon-pulse 2s infinite;
    }
    
    @keyframes flash-icon-pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    /* Responsive design */
    @media (max-width: 640px) {
        .studiosdb-flash-message {
            margin-left: 1rem;
            margin-right: 1rem;
        }
    }
</style>
