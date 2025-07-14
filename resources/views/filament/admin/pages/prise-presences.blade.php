<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Formulaire de sélection -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-clipboard-document-check class="w-5 h-5" />
                    Sélection du cours et de la date
                </div>
            </x-slot>
            
            <x-slot name="description">
                Choisissez le cours et la date pour commencer la prise de présences
            </x-slot>
            
            {{ $this->form }}
        </x-filament::section>

        <!-- Statistiques rapides -->
        @if($selectedCours && $selectedDate)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <x-filament::stats-card 
                    label="Total membres"
                    value="{{ $this->getTableQuery()->count() }}"
                    color="primary"
                />
                <x-filament::stats-card 
                    label="Présents"
                    value="{{ $this->getTableQuery()->where('status', 'present')->count() }}"
                    color="success"
                />
                <x-filament::stats-card 
                    label="Absents"
                    value="{{ $this->getTableQuery()->where('status', 'absent')->count() }}"
                    color="danger"
                />
                <x-filament::stats-card 
                    label="Retards"
                    value="{{ $this->getTableQuery()->where('status', 'retard')->count() }}"
                    color="warning"
                />
            </div>
        @endif

        <!-- Table des présences -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center justify-between w-full">
                    <span>Présences</span>
                    @if($selectedCours && $selectedDate)
                        <span class="text-sm text-gray-500">
                            {{ \App\Models\Cours::find($selectedCours)?->nom }} - {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}
                        </span>
                    @endif
                </div>
            </x-slot>
            
            {{ $this->table }}
        </x-filament::section>
    </div>
</x-filament-panels::page>
