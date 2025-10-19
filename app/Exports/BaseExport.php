<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class BaseExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    protected $records;

    public function __construct($records)
    {
        $this->records = $records;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->records;
    }

    public function headings(): array
    {
        return $this->getHeadings();
    }

    /**
     * @param  mixed  $row
     */
    public function map($row): array
    {
        return $this->mapRow($row);
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * Get the headings for the export
     */
    abstract protected function getHeadings(): array;

    /**
     * Map a row to an array for export
     *
     * @param  mixed  $row
     */
    abstract protected function mapRow($row): array;
}
