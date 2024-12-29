<?php

namespace App\Exports;

use App\Models\Absen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookingsExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $today = now()->toDateString();
        $query = Absen::with(['dutyOfficer']); // Adjust relations if necessary

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return $query->whereDate('tanggal', $today)->get()->map(function ($item) {
            return [
                'Nama' => $item->name,
                'Tanggal' => $item->tanggal,
                'Status' => $item->status,
                'Duty Officer' => $item->dutyOfficer->nama_do ?? 'N/A',
                'Tanda Tangan' => $item->signature ? 'data:image/png;base64,' . base64_encode($item->signature) : 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Tanggal',
            'Status',
            'Duty Officer',
            'Tanda Tangan',
        ];
    }
}
