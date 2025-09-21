<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    use HasFactory;

    protected $table = 'contact_infos';

    protected $fillable = [
        'title',
        'content',
        'phone',
        'email',
        'address',
        'map_embed_url',
        'social_media_links',
    ];

    protected $casts = [
        'social_media_links' => 'array', 
    ];
}

