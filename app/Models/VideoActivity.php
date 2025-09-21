<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'video_url',
        'is_active',
        'order',
    ];

    // Opsional: Jika Anda ingin menambahkan casting untuk boolean
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
