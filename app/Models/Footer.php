<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    use HasFactory;

    // Nama tabel yang terkait dengan model ini
    protected $table = 'footers';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'copyright_text',
        'address_short',
        'phone_short',
        'email_short',
        'quick_links',
    ];

    // Konversi tipe data untuk kolom tertentu
    protected $casts = [
        'quick_links' => 'array', // Mengubah quick_links menjadi array PHP
    ];
}
