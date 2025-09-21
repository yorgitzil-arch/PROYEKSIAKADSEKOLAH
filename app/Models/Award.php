<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    use HasFactory;

    // Nama tabel yang terkait dengan model ini
    protected $table = 'awards';

    // Kolom-kolom yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'title',
        'description',
        'awarded_by',
        'award_date',
        'image_path',
    ];

    // Konversi tipe data untuk kolom tertentu
    protected $casts = [
        'award_date' => 'date', // Mengubah award_date menjadi objek Carbon (tanggal saja)
    ];
}

