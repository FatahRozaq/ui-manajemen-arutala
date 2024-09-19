<?php
namespace App\Imports;

use App\Models\Pendaftar;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PendaftarImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Pendaftar([
            'nama' => $row['nama'],            // kolom B
            'email' => $row['email'],          // kolom C
            'no_kontak' => $row['no_kontak'],  // kolom D
            'password' => '$2a$12$pG4Itrg5QIhVx5u3JN4w5edP1xJMlIUwykkZgqNHGKmfvB1tI9P1i', // kolom default untuk hash password
            'aktivitas' => $row['aktivitas'],  // kolom E
            'nama_instansi' => $row['nama_instansi'],  // kolom F
            'provinsi' => $row['provinsi'],    // kolom G
            'kab_kota' => $row['kab_kota'],    // kolom H
            'linkedin' => $row['linkedin'],    // kolom I
            'created_by' => 'Import Data',     // kolom default
            'created_time' => now(),
        ]);
    }
}
