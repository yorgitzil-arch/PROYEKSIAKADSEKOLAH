<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolProfile extends Model
{
    use HasFactory;

    protected $table = 'school_profiles';

    protected $fillable = [
        'name',
        'history',
        'vision',
        'mission',
        'address',
        'phone',
        'email',
        'website',
        'logo_path',
        'banner_path',
    ];
}

