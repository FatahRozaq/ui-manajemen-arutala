<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $table = 'sertifikat';
    protected $primaryKey = 'id_sertifikat';

    protected $fillable = [
        'id_sertifikat',
        'id_pendaftar',
        'id_pendaftaran',
        'file_sertifikat',
        'created_by',
        'created_time',
        'modified_by',
        'modified_time',
        'is_deleted'
    ];

    public $timestamps = false;

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class, 'id_pendaftar');
    }

    public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranEvent::class, 'id_pendaftaran');
    }
}
