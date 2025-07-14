<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use App\Models\SessionCours;
use App\Models\Presence;
use App\Models\User;
use App\Models\Cours;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;

class PrisePresences extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static string $view = 'filament.admin.pages.prise-presences';
    protected static ?string $navigationGroup = 'Gestion École';
    protected static ?string $title = 'Prise de Présences';
    protected static ?int $navigationSort = 3;

    public ?array $data = [];
    public ?int $selectedCours = null;
    public ?string $selectedDate = null;

    public function mount(): void
    {
        $this->selectedDate = today()->format('Y-m-d');
        $this->form->fill([
            'date' => $this->selectedDate,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('cours_id')
                    ->label('Cours')
                    ->options(function () {
                        $user = auth()->user();
                        $query = Cours::where('actif', true);

                        if (!$user->hasRole('superadmin')) {
                            $query->where('ecole_id', $user->ecole_id);
                        }

                        return $query->pluck('nom', 'id');
                    })
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(fn ($state) => $this->selectedCours = $state),

                DatePicker::make('date')
                    ->label('Date')
                    ->default(today())
                    ->reactive()
                    ->afterStateUpdated(fn ($state) => $this->selectedDate = $state),
            ])
            ->statePath('data')
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getPresencesQuery())
            ->columns([
                TextColumn::make('user.code_utilisateur')
                    ->label('Code')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('user.nom_complet')
                    ->label('Membre')
                    ->searchable(['nom', 'prenom']),

                BadgeColumn::make('user.ceinture_actuelle.nom')
                    ->label('Ceinture')
                    ->colors([
                        'warning' => 'Blanche',
                        'primary' => 'Jaune',
                        'success' => 'Orange',
                        'danger' => 'Verte',
                        'secondary' => 'Bleue',
                        'info' => 'Brune',
                        'dark' => 'Noire',
                    ]),

                SelectColumn::make('status')
                    ->label('Statut')
                    ->options([
                        'present' => 'Présent',
                        'absent' => 'Absent',
                        'retard' => 'Retard',
                        'excuse' => 'Excusé',
                    ])
                    ->selectablePlaceholder(false)
                    ->afterStateUpdated(function ($record, $state) {
                        $record->update([
                            'status' => $state,
                            'heure_arrivee' => $state === 'present' ? now()->format('H:i:s') : null,
                            'marque_par_id' => auth()->id(),
                        ]);

                        Notification::make()
                            ->title('Présence mise à jour')
                            ->success()
                            ->send();
                    }),

                TextColumn::make('heure_arrivee')
                    ->label('Heure arrivée')
                    ->time('H:i')
                    ->placeholder('—'),
            ])
            ->actions([
                Action::make('marquer_present')
                    ->label('Présent')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'present',
                            'heure_arrivee' => now()->format('H:i:s'),
                            'marque_par_id' => auth()->id(),
                        ]);
                        
                        Notification::make()
                            ->title('Marqué présent')
                            ->success()
                            ->send();
                    }),

                Action::make('marquer_absent')
                    ->label('Absent')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'absent',
                            'heure_arrivee' => null,
                            'marque_par_id' => auth()->id(),
                        ]);
                        
                        Notification::make()
                            ->title('Marqué absent')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                // Actions en lot pour marquer plusieurs présences
            ])
            ->emptyStateHeading('Sélectionnez un cours et une date')
            ->emptyStateDescription('Choisissez un cours et une date pour voir les membres inscrits et prendre les présences.')
            ->emptyStateIcon('heroicon-o-clipboard-document-check');
    }

    private function getPresencesQuery()
    {
        if (!$this->selectedCours || !$this->selectedDate) {
            return Presence::query()->whereRaw('1 = 0');
        }

        // Créer ou récupérer la session du jour
        $session = SessionCours::firstOrCreate([
            'cours_id' => $this->selectedCours,
            'date' => $this->selectedDate,
        ], [
            'heure_debut' => '19:00:00', // Heure par défaut
            'heure_fin' => '20:00:00',
            'instructeur_id' => auth()->id(),
            'statut' => 'planifie',
        ]);

        // Récupérer les membres inscrits au cours
        $membres = User::whereHas('inscriptions', function ($query) {
            $query->where('cours_id', $this->selectedCours)
                  ->where('statut', 'active');
        })->get();

        // Créer les présences manquantes
        foreach ($membres as $membre) {
            Presence::firstOrCreate([
                'session_cours_id' => $session->id,
                'user_id' => $membre->id,
            ], [
                'status' => 'absent', // Par défaut absent
                'marque_par_id' => auth()->id(),
            ]);
        }

        return Presence::where('session_cours_id', $session->id)
            ->with(['user', 'user.ceintures']);
    }
}
