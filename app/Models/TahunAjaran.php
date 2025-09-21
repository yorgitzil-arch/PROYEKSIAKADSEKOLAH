<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajarans'; 

    protected $fillable = [
        'nama',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }

    public function rekapNilaiMapel()
    {
        return $this->hasMany(RekapNilaiMapel::class);
    }

    public function ekstrakurikulerSiswa()
    {
        return $this->hasMany(EkstrakurikulerSiswa::class);
    }

    public function raports()
    {
        return $this->hasMany(Raport::class);
    }

    public function presensiAkhir()
    {
        return $this->hasMany(PresensiAkhir::class);
    }

    public function catatanWaliKelas()
    {
        return $this->hasMany(CatatanWaliKelas::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
