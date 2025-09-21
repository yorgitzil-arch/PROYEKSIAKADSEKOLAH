<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Guru extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'guru';

    protected $table = 'gurus'; 

    protected $fillable = [
        'nip',
        'name',
        'email',
        'password',
        'is_wali_kelas',
        'kategori',
        'profile_picture', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_wali_kelas' => 'boolean',
    ];

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function teachingMaterials()
    {
        return $this->hasMany(TeachingMaterial::class);
    }

    public function appreciations()
    {
        return $this->hasMany(Appreciation::class);
    }

    public function studentAnnouncements()
    {
        return $this->hasMany(GuruStudentAnnouncement::class);
    }

    public function assignmentsGiven()
    {
        return $this->hasMany(AssignmentGiven::class);
    }

    public function attendances()
    {
        return $this->hasMany(TeacherAttendance::class);
    }

    public function kelasWali()
    {
        return $this->hasOne(Kelas::class, 'wali_kelas_id');
    }

    public function rekapNilaiMapel()
    {
        return $this->hasMany(RekapNilaiMapel::class, 'guru_pengampu_id');
    }

    public function raports()
    {
        return $this->hasMany(Raport::class, 'wali_kelas_id');
    }

    public function nilaiAkademikCreated()
    {
        return $this->hasMany(NilaiAkademik::class, 'created_by_guru_id');
    }

    public function nilaiKeterampilanCreated()
    {
        return $this->hasMany(NilaiKeterampilan::class, 'created_by_guru_id');
    }

    public function nilaiSikapCreated()
    {
        return $this->hasMany(NilaiSikap::class, 'created_by_guru_id');
    }

    public function presensiAkhirCreated()
    {
        return $this->hasMany(PresensiAkhir::class, 'created_by_guru_id');
    }

    public function catatanWaliKelasCreated()
    {
        return $this->hasMany(CatatanWaliKelas::class, 'created_by_guru_id');
    }
}
