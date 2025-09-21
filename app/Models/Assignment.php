<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'guru_id',
        'mata_pelajaran_id',
        'kelas_id',
        'tahun_ajaran_id', 
        'semester_id',  
        'tipe_mengajar',
        'status_konfirmasi',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }


    public function nilaiAkademik()
    {
        return $this->hasMany(NilaiAkademik::class);
    }

    public function nilaiKeterampilan()
    {
        return $this->hasMany(NilaiKeterampilan::class);
    }

    public function nilaiSikap()
    {
        return $this->hasMany(NilaiSikap::class);
    }

    public function lessonSchedules()
    {
        return $this->hasMany(LessonSchedule::class);
    }
}