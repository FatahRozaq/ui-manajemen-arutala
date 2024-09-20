<?php
namespace App\Imports;

use App\Models\Pendaftar;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Hash; // Import Hash facade

class PendaftarImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Pendaftar([
            'nama' => $row[1],
            'email' => $row[2], 
            'no_kontak' => $row[3],
            'password' => Hash::make($row[4]), // Hash password
            'aktivitas' => $row[5],
            'nama_instansi' => $row[6],
            'provinsi' => strtoupper($row[7]), // Ubah provinsi menjadi uppercase
            'kab_kota' => strtoupper($row[8]), // Ubah kab_kota menjadi uppercase
            'linkedin' => $row[9],
            'created_by' => 'Admin', // misalnya
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
