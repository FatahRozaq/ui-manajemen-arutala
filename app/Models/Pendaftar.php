<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pendaftar extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

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
        'modified_time',
        'is_deleted'
    ];

    // public $timestamps = false;  

    const CREATED_AT = 'created_time';
    const UPDATED_AT = 'modified_time';

    // public $timestamps = false;

    public function pendaftaranEvent()
    {
        return $this->hasMany(PendaftaranEvent::class, 'id_peserta', 'id_pendaftar');
    }

}