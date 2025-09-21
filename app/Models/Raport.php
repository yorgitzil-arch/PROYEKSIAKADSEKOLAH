<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raport extends Model
{
    use HasFactory;

    protected $table = 'raports';

    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'wali_kelas_id',
        'tahun_ajaran_id',
        'semester_id',
        'catatan_wali_kelas',
        'jumlah_sakit',
        'jumlah_izin',
        'jumlah_alfa',
        'rata_rata_nilai',
        'peringkat_ke',
        'tanggal_cetak',
        'tempat_cetak',
        'status_final',
        'kepala_sekolah_nama',
        'kepala_sekolah_nip',
         'status_kenaikan_kelas',
        'saran_kenaikan_kelas',
    ];

    protected $casts = [
        'tanggal_cetak' => 'date',
        'status_final' => 'boolean',
        'rata_rata_nilai' => 'decimal:2',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function ekstrakurikulerRaport() 
    {
        return $this->hasMany(RaportEkstrakurikuler::class);
    }
}
