<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonSchedule extends Model
{
    use HasFactory;

    protected $table = 'lesson_schedules';

    protected $fillable = [
        'assignment_id',
        'date',
        'start_time',
        'end_time',
        'topic',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
