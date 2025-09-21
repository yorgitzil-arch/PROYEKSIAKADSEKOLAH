<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'jurusan_id',
        'nama_kelas',
        'tingkat',
        'wali_kelas_id',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
    }

    public function rekapNilaiMapel()
    {
        return $this->hasMany(RekapNilaiMapel::class);
    }

    public function raports()
    {
        return $this->hasMany(Raport::class);
    }
}
