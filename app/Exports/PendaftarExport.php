<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\Exportable;

class PendaftarExport implements FromCollection, WithHeadings, WithStyles
{
    use Exportable;

    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $modifiedData = [];

        foreach ($this->data as $index => $item) {
            $modifiedData[] = [
                'no' => $index + 1,
                'nama' => $item->nama,
                'email' => $item->email,
                'no_kontak' => $item->no_kontak,
                'aktivitas' => $item->aktivitas,
                'nama_instansi' => $item->nama_instansi,
                'provinsi' => $item->provinsi,
                'kab_kota' => $item->kab_kota,
                'linkedin' => $item->linkedin
            ];
        }

        return collect($modifiedData);
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Email',
            'Kontak',
            'Aktivitas',
            'Nama Instansi',
            'Provinsi',
            'Kab/Kota',
            'Linkedin'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set the styles for the header row
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'color' => ['rgb' => 'FFFFFF'], // White text
                'bold' => true, // Bold text for headings
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '000A65', // Background color
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Auto size columns to fit content
        foreach (range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
}
