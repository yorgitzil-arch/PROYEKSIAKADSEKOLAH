<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarouselItem extends Model
{
    use HasFactory;

    // Nama tabel yang terkait dengan model ini
    protected $table = 'carousel_items';

    // Kolom-kolom yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'title',
        'description',
        'image_path',
        'order',
        'is_active',
    ];

    // Konversi tipe data untuk kolom tertentu
    protected $casts = [
        'is_active' => 'boolean', // Mengubah is_active menjadi boolean
    ];
}

