<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaportEkstrakurikuler extends Model
{
    use HasFactory;

    protected $table = 'raport_ekstrakurikuler';

    protected $fillable = [
        'raport_id',
        'nama_ekskul',
        'jenis_ekskul',
        'predikat',
    ];

    public function raport()
    {
        return $this->belongsTo(Raport::class);
    }
}
