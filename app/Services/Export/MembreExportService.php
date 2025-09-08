<?php

declare(strict_types=1);

namespace App\Services\Export;

use App\Models\Membre;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Response as ResponseFactory;

final class MembreExportService
{
    /**
     * Exporte en Excel si "maatwebsite/excel" installé, sinon CSV.
     *
     * @param  \Illuminate\Support\Collection<int, \App\Models\Membre>  $membres
     */
    public function excelOrCsv(Collection $membres): Response
    {
        $filename = 'membres_'.Date::now()->format('Ymd_His');

        if (class_exists(\Maatwebsite\Excel\Facades\Excel::class)) {
            // Export Excel
            $rows = $this->mapRows($membres);
            $export = new class($rows) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings
            {
                public function __construct(private array $rows) {}

                public function array(): array
                {
                    return $this->rows;
                }

                public function headings(): array
                {
                    return array_keys($this->rows[0] ?? ['ID' => 'ID']);
                }
            };
            /** @var \Maatwebsite\Excel\Facades\Excel $excel */
            $excel = app(\Maatwebsite\Excel\Facades\Excel::class);

            return $excel::download($export, "{$filename}.xlsx");
        }

        // Fallback CSV natif
        $csv = $this->toCsv($this->mapRows($membres));

        return ResponseFactory::make($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ]);
    }

    /**
     * Exporte en PDF si barryvdh/dompdf installé, sinon HTML simple téléchargeable.
     *
     * @param  \Illuminate\Support\Collection<int, \App\Models\Membre>  $membres
     */
    public function pdfOrHtml(Collection $membres): Response
    {
        $filename = 'membres_'.Date::now()->format('Ymd_His');
        $rows = $this->mapRows($membres);

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $html = view('exports.membres', ['rows' => $rows])->render();
            /** @var \Barryvdh\DomPDF\Facade\Pdf $pdf */
            $pdf = app(\Barryvdh\DomPDF\Facade\Pdf::class);

            return $pdf::loadHTML($html)->download("{$filename}.pdf");
        }

        // Fallback HTML téléchargeable
        $html = '<!doctype html><meta charset="utf-8"><title>Export Membres</title><style>table{border-collapse:collapse}td,th{border:1px solid #999;padding:6px}</style><table><thead><tr>';
        foreach (array_keys($rows[0] ?? []) as $h) {
            $html .= '<th>'.e($h).'</th>';
        }
        $html .= '</tr></thead><tbody>';
        foreach ($rows as $r) {
            $html .= '<tr>';
            foreach ($r as $v) {
                $html .= '<td>'.e((string) $v).'</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';

        return ResponseFactory::make($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}.html\"",
        ]);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, \App\Models\Membre>  $membres
     * @return array<int, array<string, mixed>>
     */
    private function mapRows(Collection $membres): array
    {
        return $membres->map(function (Membre $m) {
            return [
                'ID' => $m->id,
                'Nom' => $m->nom ?? '',
                'Prénom' => $m->prenom ?? '',
                'Email' => $m->user->email ?? '',
                'Téléphone' => $m->telephone ?? '',
                'Statut' => $m->statut ?? '',
                'Ceinture' => $m->ceintureActuelle->nom ?? '',
                'Inscription' => optional($m->date_inscription)->format('Y-m-d'),
                'Dernière présence' => optional($m->date_derniere_presence)->format('Y-m-d'),
            ];
        })->all();
    }

    /** @param array<int, array<string, mixed>> $rows */
    private function toCsv(array $rows): string
    {
        if (empty($rows)) {
            return "ID\n";
        } // en-tête minimal
        $out = fopen('php://temp', 'r+');
        fputcsv($out, array_keys($rows[0]));
        foreach ($rows as $r) {
            fputcsv($out, array_map(static fn ($v) => is_scalar($v) ? $v : json_encode($v), $r));
        }
        rewind($out);

        return stream_get_contents($out) ?: '';
    }
}
