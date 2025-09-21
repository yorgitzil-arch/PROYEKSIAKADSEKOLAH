<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Pastikan ini ada

class News extends Model
{
    use HasFactory;

    // Kolom-kolom yang bisa diisi secara massal
    protected $fillable = [
        'title',
        'slug',
        'image_path',
        'short_description',
        'content',
        'source_url',
        'published_at', // Pastikan ini ada di database dan diisi
    ];

    // Kolom-kolom yang harus secara otomatis dikonversi ke tipe data tertentu
    protected $casts = [
        'published_at' => 'datetime', // Ini PENTING agar Anda bisa memanggil ->format()
    ];

    /**
     * Metode 'boot' akan dijalankan saat model dimuat.
     * Digunakan untuk menangani event 'creating' dan 'updating'.
     */
    protected static function boot()
    {
        parent::boot();

        // Otomatis membuat slug dari judul saat membuat berita baru
        static::creating(function ($news) {
            $news->slug = Str::slug($news->title);
            // Anda mungkin juga ingin mengisi published_at secara otomatis di sini
            // if (empty($news->published_at)) {
            //     $news->published_at = now(); // Set ke waktu saat ini jika belum diatur
            // }
        });

        // Otomatis memperbarui slug jika judul berubah saat memperbarui berita
        static::updating(function ($news) {
            if ($news->isDirty('title')) {
                $news->slug = Str::slug($news->title);
            }
        });
    }

    // CATATAN:
    // Metode 'getRouteKeyName()' di bawah ini adalah yang menyebabkan
    // error 404 pada admin panel karena memaksa rute menggunakan 'slug'
    // padahal Anda mengirim 'id' dari view.
    // Jika Anda ingin admin panel menggunakan ID, pastikan blok ini DIHAPUS atau DIKOMENTARI.
    /*
    public function getRouteKeyName()
    {
        return 'slug';
    }
    */
}
