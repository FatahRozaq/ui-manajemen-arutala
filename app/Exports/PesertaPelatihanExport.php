<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PesertaPelatihanExport implements FromCollection, WithHeadings
{
    protected $pendaftaranEvents;

    public function __construct($pendaftaranEvents)
    {
        // Konversi data menjadi Laravel Collection jika belum
        $this->pendaftaranEvents = collect($pendaftaranEvents);
    }

    public function collection()
    {
        // Jika data adalah array sederhana tanpa relasi
        return $this->pendaftaranEvents->map(function ($event) {
            return [
                'Nama Pelatihan' => $event['nama_pelatihan'],
                'Batch' => $event['batch'],
                'Nama Peserta' => $event['nama_peserta'],
                'No Kontak' => $event['no_kontak'],
                'Status Pembayaran' => $event['status_pembayaran'],
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
