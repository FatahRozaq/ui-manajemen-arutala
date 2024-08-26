<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    use HasFactory;

    protected $table = 'pendaftar';
    protected $primaryKey = 'id_pendaftar';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_kontak',
        'aktivitas',
        'nama_instansi',
        'provinsi',
        'kab_kota',
        'linkedin',
        'created_by',
        'created_time',
        'modified_by',
        'modified_time'
    ];

    public $timestamps = false;
}
