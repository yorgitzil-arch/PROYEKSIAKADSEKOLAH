<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapNilaiMapel extends Model
{
    use HasFactory;

    protected $table = 'rekap_nilai_mapels'; 

    protected $fillable = [
        'siswa_id',
        'mapel_id',
        'guru_pengampu_id',
        'kelas_id',
        'tahun_ajaran_id',
        'semester_id',
        'kkm_mapel',
        'nilai_pengetahuan_angka',
        'nilai_pengetahuan_predikat',
        'deskripsi_pengetahuan',
        'nilai_keterampilan_angka',
        'nilai_keterampilan_predikat',
        'deskripsi_keterampilan',
        'nilai_sikap_spiritual_predikat',
        'deskripsi_sikap_spiritual',
        'nilai_sikap_sosial_predikat',
        'deskripsi_sikap_sosial',
    ];

    protected $casts = [
        'nilai_pengetahuan_angka' => 'decimal:2',
        'nilai_keterampilan_angka' => 'decimal:2',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    public function guruPengampu()
    {
        return $this->belongsTo(Guru::class, 'guru_pengampu_id');
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

    public static function getPredikat($nilai)
    {
        if ($nilai >= 90) {
            return 'A';
        } elseif ($nilai >= 80) {
            return 'B';
        } elseif ($nilai >= 70) {
            return 'C';
        } else {
            return 'D';
        }
    }
}
