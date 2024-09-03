<?php

namespace App\Imports;

use App\Models\Pendaftar;
use Maatwebsite\Excel\Concerns\ToModel;

class PendaftarImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Pendaftar([
            'nama' => $row[0],
            'email' => $row[1],
            'no_kontak' => $row[2],
            'password' => '$2a$12$pG4Itrg5QIhVx5u3JN4w5edP1xJMlIUwykkZgqNHGKmfvB1tI9P1i',
            'aktivitas' => $row[3],
            'nama_instansi' => $row[4],
            'provinsi' => $row[5],
            'kab_kota' => $row[6],
            'linkedin' => $row[7],
            'created_by' => 'Import Data', // misalnya
            'created_time' => now(),
        ]);
    }
}
