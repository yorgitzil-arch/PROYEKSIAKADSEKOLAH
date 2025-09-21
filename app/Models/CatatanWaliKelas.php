<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanWaliKelas extends Model
{
    use HasFactory;

    protected $table = 'catatan_wali_kelas';

    protected $fillable = [
        'siswa_id',
        'tahun_ajaran_id',
        'semester_id',
        'catatan',
        'created_by_guru_id',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function createdByGuru()
    {
        return $this->belongsTo(Guru::class, 'created_by_guru_id');
    }
}
