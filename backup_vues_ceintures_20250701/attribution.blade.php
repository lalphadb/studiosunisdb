@extends('layouts.admin')

@section('title', 'Attribution Ceintures')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <x-module-header 
        module="ceintures"
        title="Attribution de Ceintures"
        subtitle="Attribution rapide pour examens et promotions"
        :createRoute="null"
    />

    <!-- Formulaire Attribution Masse -->
    <div class="studiosdb-card">
        <form method="POST" action="{{ route('admin.ceintures.attribution.store') }}" x-data="attributionForm()">
            @csrf
            
            <div class="mb-6">
                <h3 class="text-lg font-bold text-white mb-4">🎯 Nouvel Examen / Attribution</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <!-- Nouvelle Ceinture -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Nouvelle Ceinture</label>
                        <select name="nouvelle_ceinture_id" required 
                                class="studiosdb-select" x-model="nouvelleCeinture">
                            <option value="">Sélectionner...</option>
                            @foreach($ceintures as $ceinture)
                                <option value="{{ $ceinture->id }}">
                                    {{ $ceinture->nom }} (Ordre {{ $ceinture->ordre }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Date Obtention -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Date d'Obtention</label>
                        <input type="date" name="date_obtention" required 
                               value="{{ date('Y-m-d') }}" class="studiosdb-input">
                    </div>
                    
                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Notes (optionnel)</label>
                        <input type="text" name="notes" placeholder="Ex: Examen décembre 2025" 
                               class="studiosdb-input">
                    </div>
                </div>
            </div>

            <!-- Sélection Membres -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-md font-bold text-white">👥 Sélectionner les Membres</h4>
                    <div class="space-x-2">
                        <button type="button" @click="selectAll()" 
                                class="studiosdb-btn studiosdb-btn-secondary text-xs">
                            Tout Sélectionner
                        </button>
                        <button type="button" @click="selectNone()" 
                                class="studiosdb-btn studiosdb-btn-cancel text-xs">
                            Tout Désélectionner
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 max-h-96 overflow-y-auto border border-slate-600 rounded-lg p-4">
                    @foreach($membres as $membre)
                        <label class="flex items-center p-3 bg-slate-700/30 rounded-lg hover:bg-slate-600/30 cursor-pointer transition-colors">
                            <input type="checkbox" name="membres[]" value="{{ $membre->id }}" 
                                   class="rounded border-slate-500 text-orange-500 focus:ring-orange-500"
                                   x-model="selectedMembres">
                            <div class="ml-3 flex-1">
                                <div class="text-white font-medium">{{ $membre->name }}</div>
                                <div class="text-slate-400 text-xs">
                                    Actuelle: {{ $membre->ceinture_actuelle->nom ?? 'Aucune' }}
                                </div>
                            </div>
                            @if($membre->ceinture_actuelle)
                                <div class="w-4 h-4 rounded-full ml-2" 
                                     style="background-color: {{ $membre->ceinture_actuelle->couleur }}"></div>
                            @endif
                        </label>
                    @endforeach
                </div>
                
                <div class="mt-3 text-sm text-slate-400">
                    <span x-text="selectedMembres.length"></span> membre(s) sélectionné(s)
                </div>
            </div>

            <!-- Bouton Attribution -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="studiosdb-btn studiosdb-btn-ceintures"
                        :disabled="selectedMembres.length === 0 || !nouvelleCeinture">
                    <span x-show="selectedMembres.length === 0">Sélectionner des membres</span>
                    <span x-show="selectedMembres.length > 0" x-text="'Attribuer à ' + selectedMembres.length + ' membre(s)'"></span>
                </button>
            </div>
        </form>
    </div>

    <!-- Attributions Récentes -->
    <div class="studiosdb-card">
        <h3 class="text-lg font-bold text-white mb-4">📋 Attributions Récentes (30 derniers jours)</h3>
        
        @if($attributions_recentes->count() > 0)
            <div class="space-y-2">
                @foreach($attributions_recentes as $attribution)
                    <div class="flex items-center justify-between p-3 bg-slate-700/20 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 rounded-full" 
                                 style="background-color: {{ $attribution->ceinture->couleur }}"></div>
                            <div>
                                <span class="text-white font-medium">{{ $attribution->user->name }}</span>
                                <span class="text-slate-400 mx-2">→</span>
                                <span class="text-orange-400">{{ $attribution->ceinture->nom }}</span>
                            </div>
                        </div>
                        <div class="text-slate-400 text-sm">
                            {{ $attribution->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-slate-400">
                <p>Aucune attribution récente</p>
            </div>
        @endif
    </div>
</div>

<script>
function attributionForm() {
    return {
        selectedMembres: [],
        nouvelleCeinture: '',
        
        selectAll() {
            this.selectedMembres = @json($membres->pluck('id'));
        },
        
        selectNone() {
            this.selectedMembres = [];
        }
    }
}
</script>
@endsection
