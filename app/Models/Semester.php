<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $table = 'semesters';

    protected $fillable = [
        'tahun_ajaran_id',
        'nama', 
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

   
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function nilaiAkademik()
    {
        return $this->hasMany(NilaiAkademik::class, 'semester_id'); 
    }

    public function nilaiKeterampilan()
    {
        return $this->hasMany(NilaiKeterampilan::class, 'semester_id'); 
    }

    public function nilaiSikap()
    {
        return $this->hasMany(NilaiSikap::class, 'semester_id'); 
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'semester_id'); 
    }

    public function presensiAkhir()
    {
        return $this->hasMany(PresensiAkhir::class, 'semester_id');
    }

    public function catatanWaliKelas()
    {
        return $this->hasMany(CatatanWaliKelas::class, 'semester_id'); 
    }

    public function rekapNilaiMapel()
    {
        return $this->hasMany(RekapNilaiMapel::class, 'semester_id'); 
    }

    public function ekstrakurikulerSiswa()
    {
        return $this->hasMany(EkstrakurikulerSiswa::class, 'semester_id'); 
    }

    public function raports()
    {
        return $this->hasMany(Raport::class, 'semester_id'); 
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
