<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $table = 'assignment_submissions'; 

    protected $fillable = [
        'assignment_given_id',
        'siswa_id',
        'file_path',
        'notes',
        'score',
        'feedback',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];


    public function assignmentGiven()
    {
        return $this->belongsTo(AssignmentGiven::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
