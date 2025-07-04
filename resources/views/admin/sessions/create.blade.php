@extends('layouts.admin')

@section('title', 'Créer une session')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <x-module-header 
        module="sessions"
        title="Créer une nouvelle session" 
        subtitle="Définissez les dates et paramètres de votre session saisonnière"
    />

    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
            <li>
                <a href="{{ route('admin.sessions.index') }}" class="text-slate-400 hover:text-white transition-colors flex items-center">
                    <x-admin-icon name="calendar" size="w-5 h-5" class="mr-1" />
                    Sessions
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <x-admin-icon name="chevron-right" size="w-5 h-5" color="text-slate-500" class="mx-2" />
                    <span class="text-sm font-medium text-slate-300">Nouvelle session</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Messages d'erreur -->
    @if ($errors->any())
        <div class="studiosdb-card border-l-4 border-red-500 bg-red-900/20">
            <div class="flex">
                <x-admin-icon name="exclamation-triangle" size="w-5 h-5" color="text-red-400" class="mr-3 mt-0.5" />
                <div>
                    <h3 class="text-sm font-medium text-red-200 mb-2">
                        Erreurs dans le formulaire
                    </h3>
                    <ul class="list-disc list-inside space-y-1 text-sm text-red-300">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Formulaire principal -->
    <form method="POST" action="{{ route('admin.sessions.store') }}" class="space-y-6" x-data="sessionForm()">
        @csrf

        <!-- Informations de base -->
        <div class="studiosdb-card">
            <div class="border-b border-slate-700/50 pb-4 mb-6">
                <h3 class="text-lg font-medium text-white flex items-center">
                    <x-admin-icon name="information-circle" size="w-5 h-5" class="mr-2 text-violet-400" />
                    Informations de la session
                </h3>
                <p class="mt-1 text-sm text-slate-400">
                    Définissez le nom, les dates et la description de votre nouvelle session.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom de la session -->
                <div class="md:col-span-2">
                    <label for="nom" class="block text-sm font-medium text-slate-300 mb-2">
                        Nom de la session <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="nom" 
                           id="nom" 
                           value="{{ old('nom') }}"
                           placeholder="Ex: Automne 2025, Trimestre 1, Session Intensive..."
                           class="studiosdb-input w-full"
                           required
                           x-model="form.nom">
                    <p class="mt-1 text-xs text-slate-500">
                        Choisissez un nom descriptif : "Automne 2025", "Cycle débutant", "Session intensive", etc.
                    </p>
                </div>

                <!-- Date de début -->
                <div>
                    <label for="date_debut" class="block text-sm font-medium text-slate-300 mb-2">
                        Date de début <span class="text-red-400">*</span>
                    </label>
                    <input type="date" 
                           name="date_debut" 
                           id="date_debut" 
                           value="{{ old('date_debut') }}"
                           class="studiosdb-input w-full"
                           required
                           x-model="form.date_debut"
                           @change="updateDuree()">
                </div>

                <!-- Date de fin -->
                <div>
                    <label for="date_fin" class="block text-sm font-medium text-slate-300 mb-2">
                        Date de fin <span class="text-red-400">*</span>
                    </label>
                    <input type="date" 
                           name="date_fin" 
                           id="date_fin" 
                           value="{{ old('date_fin') }}"
                           class="studiosdb-input w-full"
                           required
                           x-model="form.date_fin"
                           @change="updateDuree()">
                    
                    <!-- Indicateur de durée -->
                    <p class="mt-1 text-xs text-slate-500" x-show="duree" x-text="duree"></p>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-slate-300 mb-2">
                        Description (optionnel)
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="3"
                              class="studiosdb-input w-full"
                              x-model="form.description">{{ old('description') }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">
                        Cette description sera visible par les membres lors des inscriptions.
                    </p>
                </div>

                @if(auth()->user()->hasRole('super_admin'))
                <!-- École (super admin seulement) -->
                <div class="md:col-span-2">
                    <label for="ecole_id" class="block text-sm font-medium text-slate-300 mb-2">
                        École <span class="text-red-400">*</span>
                    </label>
                    <select name="ecole_id" 
                            id="ecole_id" 
                            class="studiosdb-select w-full"
                            required>
                        <option value="">Sélectionner une école</option>
                        @foreach($ecoles as $ecole)
                            <option value="{{ $ecole->id }}" {{ old('ecole_id') == $ecole->id ? 'selected' : '' }}>
                                {{ $ecole->nom }} ({{ $ecole->code_ecole }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
        </div>

        <!-- Options d'inscription -->
        <div class="studiosdb-card">
            <div class="border-b border-slate-700/50 pb-4 mb-6">
                <h3 class="text-lg font-medium text-white flex items-center">
                    <x-admin-icon name="cog" size="w-5 h-5" class="mr-2 text-blue-400" />
                    Options d'inscription
                </h3>
                <p class="mt-1 text-sm text-slate-400">
                    Configurez la disponibilité et les paramètres d'inscription.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Date limite inscription -->
                <div>
                    <label for="date_limite_inscription" class="block text-sm font-medium text-slate-300 mb-2">
                        Date limite d'inscription
                    </label>
                    <input type="date" 
                           name="date_limite_inscription" 
                           id="date_limite_inscription" 
                           value="{{ old('date_limite_inscription') }}"
                           class="studiosdb-input w-full">
                    <p class="mt-1 text-xs text-slate-500">
                        Si vide, par défaut 1 semaine avant le début de la session
                    </p>
                </div>

                <!-- Options checkbox -->
                <div class="space-y-4">
                    <div class="flex items-start">
                        <input id="actif" 
                               name="actif" 
                               type="checkbox" 
                               value="1"
                               {{ old('actif', true) ? 'checked' : '' }}
                               class="mt-1 h-4 w-4 text-violet-500 bg-slate-700/50 border-slate-600/50 rounded focus:ring-violet-500">
                        <label for="actif" class="ml-3 block text-sm text-slate-300">
                            <span class="font-medium">Session active immédiatement</span>
                            <span class="block text-xs text-slate-500">La session sera visible et utilisable dès la création</span>
                        </label>
                    </div>

                    <div class="flex items-start">
                        <input id="inscriptions_ouvertes" 
                               name="inscriptions_ouvertes" 
                               type="checkbox" 
                               value="1"
                               {{ old('inscriptions_ouvertes') ? 'checked' : '' }}
                               class="mt-1 h-4 w-4 text-violet-500 bg-slate-700/50 border-slate-600/50 rounded focus:ring-violet-500">
                        <label for="inscriptions_ouvertes" class="ml-3 block text-sm text-slate-300">
                            <span class="font-medium">Ouvrir les inscriptions immédiatement</span>
                            <span class="block text-xs text-slate-500">⚠️ Les membres recevront un email de notification</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Duplication depuis session existante -->
        @if(isset($sessionsPrecedentes) && $sessionsPrecedentes->count() > 0)
        <div class="studiosdb-card">
            <div class="border-b border-slate-700/50 pb-4 mb-6">
                <h3 class="text-lg font-medium text-white flex items-center">
                    <x-admin-icon name="duplicate" size="w-5 h-5" class="mr-2 text-green-400" />
                    Duplication rapide
                </h3>
                <p class="mt-1 text-sm text-slate-400">
                    Copiez automatiquement les cours et horaires d'une session précédente pour gagner du temps.
                </p>
            </div>

            <div class="space-y-4" x-data="{ showOptions: false }">
                <!-- Session source -->
                <div>
                    <label for="dupliquer_depuis_session_id" class="block text-sm font-medium text-slate-300 mb-2">
                        Dupliquer depuis une session existante
                    </label>
                    <select name="dupliquer_depuis_session_id" 
                            id="dupliquer_depuis_session_id" 
                            class="studiosdb-select w-full"
                            @change="showOptions = $event.target.value !== ''">
                        <option value="">Ne pas dupliquer - Créer session vide</option>
                        @foreach($sessionsPrecedentes as $session)
                            <option value="{{ $session->id }}" {{ old('dupliquer_depuis_session_id') == $session->id ? 'selected' : '' }}>
                                {{ $session->nom }} - {{ $session->coursHoraires->count() }} horaires
                                ({{ $session->date_debut->format('M Y') }} - {{ $session->date_fin->format('M Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Options duplication -->
                <div x-show="showOptions" x-transition class="space-y-4 p-4 bg-slate-800/50 rounded-lg border border-slate-600/30">
                    <h4 class="text-sm font-medium text-slate-300 flex items-center">
                        <x-admin-icon name="adjustments" size="w-4 h-4" class="mr-2" />
                        Options de duplication
                    </h4>
                    
                    <div class="flex items-center" x-data="{ adjustPrice: false }">
                        <input id="ajuster_prix" 
                               name="ajuster_prix" 
                               type="checkbox" 
                               value="1"
                               {{ old('ajuster_prix') ? 'checked' : '' }}
                               x-model="adjustPrice"
                               class="h-4 w-4 text-violet-500 bg-slate-700/50 border-slate-600/50 rounded focus:ring-violet-500">
                        <label for="ajuster_prix" class="ml-2 block text-sm text-slate-300">
                            Ajuster les prix automatiquement
                        </label>
                        
                        <div x-show="adjustPrice" x-transition class="ml-4 flex items-center">
                            <input type="number" 
                                   name="pourcentage_augmentation" 
                                   id="pourcentage_augmentation" 
                                   value="{{ old('pourcentage_augmentation', 5) }}"
                                   min="0" 
                                   max="100"
                                   step="0.1"
                                   class="studiosdb-input w-20 text-center">
                            <span class="ml-2 text-sm text-slate-400">% d'augmentation</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex justify-end space-x-3 pt-6">
            <a href="{{ route('admin.sessions.index') }}" 
               class="studiosdb-btn studiosdb-btn-secondary">
                <x-admin-icon name="x" size="w-4 h-4" class="mr-2" />
                Annuler
            </a>
            
            <button type="submit" 
                    class="studiosdb-btn studiosdb-btn-primary"
                    :disabled="!form.nom || !form.date_debut || !form.date_fin">
                <x-admin-icon name="plus" size="w-4 h-4" class="mr-2" />
                Créer la session
            </button>
        </div>
    </form>

</div>

<script>
function sessionForm() {
    return {
        form: {
            nom: '',
            date_debut: '',
            date_fin: '',
            description: ''
        },
        duree: '',
        
        updateDuree() {
            if (this.form.date_debut && this.form.date_fin) {
                const debut = new Date(this.form.date_debut);
                const fin = new Date(this.form.date_fin);
                const diffTime = Math.abs(fin - debut);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                if (diffDays > 0) {
                    const weeks = Math.floor(diffDays / 7);
                    const months = Math.floor(diffDays / 30);
                    
                    if (months > 0) {
                        this.duree = `Durée: ${months} mois (${diffDays} jours)`;
                    } else if (weeks > 0) {
                        this.duree = `Durée: ${weeks} semaines (${diffDays} jours)`;
                    } else {
                        this.duree = `Durée: ${diffDays} jours`;
                    }
                }
            }
        }
    }
}
</script>
@endsection
