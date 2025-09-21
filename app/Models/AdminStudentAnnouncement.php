<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminStudentAnnouncement extends Model
{
    use HasFactory;

    protected $table = 'admin_student_announcements';

    protected $fillable = [
        'admin_id',
        'title',
        'message',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
