<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiSikap extends Model
{
    use HasFactory;

    protected $table = 'nilai_sikap';
    protected $fillable = [
        'assignment_id',
        'siswa_id',
        'mata_pelajaran_id', 
        'jenis_sikap',
        'deskripsi',
        'semester_id',
        'tahun_ajaran_id',
        'created_by_guru_id', 
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function mataPelajaran() 
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    public function guru() 
    {
        return $this->belongsTo(Guru::class, 'created_by_guru_id');
    }
}
