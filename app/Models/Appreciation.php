<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appreciation extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'guru_id',
        'title',
        'message',
        'category',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
