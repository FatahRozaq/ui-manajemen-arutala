<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran_Event extends Model
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
        'modified_time'
    ];

    public $timestamps = false;

    // Relasi dengan model Pendaftar
    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class, 'id_peserta');
    }

    // Relasi dengan model AgendaPelatihan
    public function agendaPelatihan()
    {
        return $this->belongsTo(AgendaPelatihan::class, 'id_agenda');
    }
}
