<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EkstrakurikulerSiswa extends Model
{
    use HasFactory;

    protected $table = 'ekstrakurikuler_siswas'; 

    protected $fillable = [
        'siswa_id',
        'tahun_ajaran_id',
        'semester_id',
        'jenis_kegiatan',
        'keterangan',
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
}