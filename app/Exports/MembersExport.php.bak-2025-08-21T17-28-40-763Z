<?php
declare(strict_types=1);

namespace App\Exports;

use App\Models\Membre;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

final class MembersExport implements FromQuery, WithHeadings, WithMapping, Responsable
{
    public string $fileName = 'membres.xlsx';

    public function __construct(
        private readonly array $filters = []
    ) {}

    public function query()
    {
        $now = CarbonImmutable::now();

        $q = Membre::query()
            ->with(['user:id,email','ceintureActuelle:id,nom'])
            ->select(['id','prenom','nom','date_naissance','telephone','adresse','statut','ceinture_actuelle_id','date_inscription']);

        if (!empty($this->filters['q'])) {
            $term = $this->filters['q'];
            $q->where(function (Builder $w) use ($term) {
                $w->where('prenom','like',"%{$term}%")
                  ->orWhere('nom','like',"%{$term}%")
                  ->orWhere('telephone','like',"%{$term}%")
                  ->orWhereHas('user', fn($u) => $u->where('email','like',"%{$term}%"));
            });
        }
        if (!empty($this->filters['statut'])) {
            $q->where('statut', $this->filters['statut']);
        }
        if (!empty($this->filters['ceinture_id'])) {
            $q->where('ceinture_actuelle_id', (int) $this->filters['ceinture_id']);
        }
        if (($this->filters['age_group'] ?? null) === 'mineur') {
            $q->whereDate('date_naissance', '>', CarbonImmutable::now()->subYears(18)->toDateString());
        } elseif (($this->filters['age_group'] ?? null) === 'adulte') {
            $q->whereDate('date_naissance', '<=', CarbonImmutable::now()->subYears(18)->toDateString());
        }

        $sort = in_array(($this->filters['sort'] ?? 'created_at'), ['created_at','nom','prenom','date_inscription'], true)
            ? $this->filters['sort'] : 'created_at';
        $dir  = in_array(($this->filters['dir'] ?? 'desc'), ['asc','desc'], true)
            ? $this->filters['dir'] : 'desc';

        return $q->orderBy($sort, $dir);
    }

    public function headings(): array
    {
        return ['ID','Prénom','Nom','Email','Téléphone','Statut','Ceinture','Date inscription','Âge'];
    }

    public function map($membre): array
    {
        $age = null;
        if ($membre->date_naissance) {
            $age = CarbonImmutable::parse($membre->date_naissance)->diffInYears(CarbonImmutable::now());
        }

        return [
            $membre->id,
            $membre->prenom,
            $membre->nom,
            $membre->user?->email,
            $membre->telephone,
            $membre->statut,
            $membre->ceintureActuelle?->nom,
            $membre->date_inscription?->format('Y-m-d'),
            $age,
        ];
    }
}
