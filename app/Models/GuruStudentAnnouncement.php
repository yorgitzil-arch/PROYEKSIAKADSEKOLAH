<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruStudentAnnouncement extends Model
{
    use HasFactory;

    protected $table = 'guru_student_announcements'; 

    protected $fillable = [
        'guru_id',
        'kelas_id',
        'title',
        'message',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
