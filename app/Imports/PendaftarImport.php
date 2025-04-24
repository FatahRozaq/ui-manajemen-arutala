<?php
namespace App\Imports;

use App\Models\Pendaftar;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Hash;

class PendaftarImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Validasi minimal: email dan password harus diisi
        if (empty($row[2]) || empty($row[4])) {
            return null; // Lewati baris jika tidak memenuhi syarat minimal
        }

        return new Pendaftar([
            'nama' => $row[1] ?? null,
            'email' => $row[2],
            'no_kontak' => $row[3] ?? null,
            'password' => Hash::make($row[4]),
            'aktivitas' => $row[5] ?? null,
            'nama_instansi' => $row[6] ?? null,
            'provinsi' => isset($row[7]) ? strtoupper($row[7]) : null,
            'kab_kota' => isset($row[8]) ? strtoupper($row[8]) : null,
            'linkedin' => $row[9] ?? null,
            'created_by' => 'Admin',
            'created_time' => now(),
        ]);
    }

    /**
     * Tentukan baris mulai.
     *
     * @return int
     */
    public function startRow(): int
    {
        return 8; 
    }
}
