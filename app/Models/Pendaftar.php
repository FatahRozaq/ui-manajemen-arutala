<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Pendaftar extends Authenticatable implements JWTSubject
{
    use Notifiable, HasFactory, HasApiTokens;

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

    const CREATED_AT = 'created_time';
    const UPDATED_AT = 'modified_time';

    // Fungsi relasi dengan tabel PendaftaranEvent
    public function pendaftaranEvent()
    {
        return $this->hasMany(PendaftaranEvent::class, 'id_peserta', 'id_pendaftar');
    }

    // Implementasi JWTSubject
    /**
     * Get the identifier that will be stored in the JWT subject claim.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
