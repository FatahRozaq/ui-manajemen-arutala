<?php

namespace App\Exports\Sheets;

use App\Models\PendaftaranEvent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaidParticipantsSheet implements FromCollection, WithHeadings
{
    protected $namaPelatihan;
    protected $batch;

    public function __construct($namaPelatihan, $batch)
    {
        $this->namaPelatihan = $namaPelatihan;
        $this->batch = $batch;
    }

    public function collection()
    {
        // Ambil data dengan filter yang diterapkan
        return PendaftaranEvent::with('pendaftar', 'agendaPelatihan.pelatihan')
            ->whereHas('agendaPelatihan.pelatihan', function ($query) {
                $query->where('nama_pelatihan', $this->namaPelatihan);
            })
            ->whereHas('agendaPelatihan', function ($query) {
                $query->where('batch', $this->batch);
            })
            ->where('status_pembayaran', 'paid')
            ->get()
            ->map(function ($event) {
                return [
                    'id_pendaftaran' => $event->id_pendaftaran,
                    'nama_pelatihan' => $event->agendaPelatihan->pelatihan->nama_pelatihan ?? '',
                    'batch' => $event->agendaPelatihan->batch ?? '',
                    'nama_peserta' => $event->pendaftar->nama ?? '',
                    'no_kontak' => $event->pendaftar->no_kontak ?? '',
                    'status_pembayaran' => $event->status_pembayaran,
                ];
            });
    }

    public function headings(): array
    {
        return ['ID Pendaftaran', 'Nama Pelatihan', 'Batch', 'Nama Peserta', 'No Kontak', 'Status Pembayaran'];
    }
}
