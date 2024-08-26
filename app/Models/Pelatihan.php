<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatihan extends Model
{
    use HasFactory;

    protected $table = 'pelatihan';
    protected $primaryKey = 'id_pelatihan';

    protected $fillable = [
        'nama_pelatihan',
        'gambar_pelatihan',
        'deskripsi',
        'benefit',
        'materi',
        'is_deleted',
        'created_by',
        'created_time',
        'modified_by',
        'modified_time'
    ];

    public $timestamps = false;

    public function agendaPelatihan()
    {
        return $this->hasMany(AgendaPelatihan::class, 'id_pelatihan', 'id_pelatihan');
    }
}
