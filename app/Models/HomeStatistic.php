<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HomeStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'value',
        'icon_class',
        'description',
        'link',
        'is_active',
        'order',
        'slug', 
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($statistic) {
            if (empty($statistic->slug)) {
                $statistic->slug = Str::slug($statistic->title);
            }
        });

        static::updating(function ($statistic) {
            if ($statistic->isDirty('title')) { 
                $statistic->slug = Str::slug($statistic->title);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
