<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; 

class Siswa extends Authenticatable
{
    use HasFactory, Notifiable; 

    protected $table = 'siswas';

    protected $fillable = [
        'user_id', 
        'nis',
        'nisn', 
        'name',
        'email',
        'password',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'nomor_telepon',
        'nama_ayah',
        'pekerjaan_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
        'jurusan_id',
        'kelas_id',
        'wali_kelas_id',
        'spp_type_id', 
        'status',
        'foto_profile_path',
        'ijazah_path',
        'raport_path',
        'kk_path',
        'ktp_ortu_path',
        'akta_lahir_path',
        'sk_lulus_path',
        'kis_path',
        'kks_path',
        
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'tanggal_lahir' => 'date',
    ];

    public function sppType()
{
    return $this->belongsTo(SppType::class);
}

public function sppPayments()
{
    return $this->hasMany(SppPayment::class);
}

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
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

    public function rekapNilaiMapel()
    {
        return $this->hasMany(RekapNilaiMapel::class);
    }

    public function presensiAkhir()
    {
        return $this->hasOne(PresensiAkhir::class);
    }
 
    public function catatanWaliKelas()
    {
        return $this->hasOne(CatatanWaliKelas::class);
    }

    public function raports()
    {
        return $this->hasMany(Raport::class);
    }

    public function assignmentSubmissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function ekstrakurikulerSiswa()
    {
        return $this->hasMany(EkstrakurikulerSiswa::class);
    }
}
