<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicTeacher extends Model
{
    use HasFactory;

    protected $table = 'public_teachers';

    protected $fillable = [
        'guru_id',
        'position',
        'category',
        'image_path',
        'display_order',
        'is_featured',
    ];

   
    protected $casts = [
        'is_featured' => 'boolean', 
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}

