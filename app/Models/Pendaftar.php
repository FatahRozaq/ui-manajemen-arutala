<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Pendaftar extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'pendaftar';  // Nama tabel

    protected $primaryKey = 'id_pendaftar';  // Primary key

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
        'modified_time',
        'is_deleted'
    ];

    public $timestamps = false;  // Menonaktifkan timestamp default Laravel

    const CREATED_AT = 'created_time';
    const UPDATED_AT = 'modified_time';
}

