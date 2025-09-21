<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; 

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'display_order',
        'is_active',
    ];

    protected static function booted()
    {
        static::creating(function ($facility) {
            $facility->slug = Str::slug($facility->name);
        });

        static::updating(function ($facility) {
            if ($facility->isDirty('name')) { 
                $facility->slug = Str::slug($facility->name);
            }
        });
    }
}
