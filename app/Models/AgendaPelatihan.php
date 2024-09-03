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

    // public function mentors()
    // {
    //     // Decode the JSON field
    //     $mentorIds = json_decode($this->attributes['id_mentor'], true);

    //     // Return related mentors
    //     return Mentor::whereIn('id_mentor', $mentorIds)->get();
    // }

    public function mentors()
    {
        // Menggunakan custom logic untuk mengambil mentor berdasarkan id_mentor yang disimpan sebagai string
        return $this->belongsToMany(Mentor::class, 'agenda_pelatihan', 'id_agenda', 'id_mentor')
            ->withPivot('id_mentor')
            ->whereNull('mentors.is_deleted'); // Mengambil mentor yang tidak dihapus
    }
}
