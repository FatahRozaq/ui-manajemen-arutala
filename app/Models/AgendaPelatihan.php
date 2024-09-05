<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaPelatihan extends Model
{
    use HasFactory;

    protected $table = 'agenda_pelatihan';
    protected $primaryKey = 'id_agenda';

    protected $fillable = [
        'start_date',
        'end_date',
        'sesi',
        'investasi',
        'investasi_info',
        'diskon',
        'status',
        'link_mayar',
        'id_mentor',
        'id_pelatihan',
        'batch',
        'start_pendaftaran',
        'end_pendaftaran',
        'created_by',
        'created_time',
        'modified_by',
        'modified_time'
    ];

    public $timestamps = false;

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'id_pelatihan', 'id_pelatihan');
    }

    public function pendaftaranEvent()
    {
        return $this->hasMany(PendaftaranEvent::class, 'id_agenda', 'id_agenda');
    }

    public function mentors()
    {
        return $this->belongsToMany(Mentor::class, 'agenda_mentor', 'id_agenda', 'id_mentor');
    }
}
