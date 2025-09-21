<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'guru_id',
        'mata_pelajaran_id',
        'kelas_id',
        'guru_assignment_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function guruAssignment() 
    {
        return $this->belongsTo(GuruAssignment::class, 'guru_assignment_id');
    }
}
