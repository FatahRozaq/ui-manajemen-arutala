<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PesertaPelatihanExport implements FromCollection, WithHeadings
{
    protected $pendaftaranEvents;

    public function __construct($pendaftaranEvents)
    {
        $this->pendaftaranEvents = $pendaftaranEvents;
    }

    public function collection()
    {
        return $this->pendaftaranEvents->map(function ($event) {
            return [
                'Nama Pelatihan' => $event->agendaPelatihan->pelatihan->nama_pelatihan,
                'Batch' => $event->agendaPelatihan->batch,
                'Nama Peserta' => $event->pendaftar->nama,
                'No Kontak' => $event->pendaftar->no_kontak,
                'Status Pembayaran' => $event->status_pembayaran,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Pelatihan',
            'Batch',
            'Nama Peserta',
            'No Kontak',
            'Status Pembayaran'
        ];
    }
}
