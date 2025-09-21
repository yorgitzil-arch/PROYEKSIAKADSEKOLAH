<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionInfo extends Model
{
    use HasFactory;

    protected $table = 'admission_infos';

    protected $fillable = [
        'title',
        'content',
        'published_at',
        'is_active',
    ];

    protected $casts = [
        'published_at' => 'datetime', 
        'is_active' => 'boolean', 
    ];
}

