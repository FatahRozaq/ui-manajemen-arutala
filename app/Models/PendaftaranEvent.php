<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranEvent extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_event';
    protected $primaryKey = 'id_pendaftaran';

    protected $fillable = [
        'id_peserta',
        'id_agenda',
        'status_pembayaran',
        'status_kehadiran',
        'created_by',
        'created_time',
        'modified_by',
        'modified_time',
        'is_deleted'
    ];

    public $timestamps = false;

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class, 'id_peserta');
    }

    public function agendaPelatihan()
    {
        return $this->belongsTo(AgendaPelatihan::class, 'id_agenda');
    }
}
