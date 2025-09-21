<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'lesson_schedule_id',
        'siswa_id',
        'assignment_id',
        'tanggal_presensi', 
        'status',
        'keterangan',
        'date',
        'tahun_ajaran_id',
        'semester_id',
    ];

    protected $casts = [
        'tanggal_presensi' => 'date', 
    ];

    public function lessonSchedule()
    {
        return $this->belongsTo(LessonSchedule::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }


    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
