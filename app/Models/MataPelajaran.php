<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajarans';

    protected $fillable = [
        'nama_mapel',
        'kode_mapel',
        'kelompok',
        'kkm',
    ];

     public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function rekapNilaiMapel()
    {
        return $this->hasMany(RekapNilaiMapel::class, 'mapel_id');
    }

    public function nilaiAkademik()
    {
        return $this->hasMany(NilaiAkademik::class, 'mata_pelajaran_id');
    }

    public function nilaiKeterampilan()
    {
        return $this->hasMany(NilaiKeterampilan::class, 'mata_pelajaran_id');
    }

    public function nilaiSikap()
    {
        return $this->hasMany(NilaiSikap::class, 'mata_pelajaran_id');
    }
}
