<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    use HasFactory;

    protected $table = 'mentor';
    protected $primaryKey = 'id_mentor';

    protected $fillable = [
        'nama_mentor',
        'email',
        'no_kontak',
        'aktivitas',
        'created_by',
        'created_time',
        'modified_by',
        'modified_time',
        'is_deleted'
    ];

    const CREATED_AT = 'created_time';
    const UPDATED_AT = 'modified_time';

    public $timestamps = false;
}
