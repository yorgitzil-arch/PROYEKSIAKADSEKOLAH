<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_sekolah',
        'nssn',
        'npsn',
        'alamat',
        'logo_kiri_path',
        'logo_kanan_path',
    ];
}

