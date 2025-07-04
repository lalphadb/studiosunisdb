<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class GenericExport implements FromQuery, WithHeadings, WithMapping, WithTitle, WithChunkReading
{
    private $query;
    private $headers;
    private $rowMapper;
    private $sheetName;

    public function __construct($query, array $headers, callable $rowMapper, string $sheetName = 'Export')
    {
        $this->query = $query;
        $this->headers = $headers;
        $this->rowMapper = $rowMapper;
        $this->sheetName = $sheetName;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function map($row): array
    {
        return ($this->rowMapper)($row);
    }

    public function title(): string
    {
        return $this->sheetName;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
