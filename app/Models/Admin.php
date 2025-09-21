<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $table = 'admins'; 

    public function appreciations()
    {
        return $this->hasMany(Appreciation::class);
    }

    public function studentAnnouncements()
    {
        return $this->hasMany(AdminStudentAnnouncement::class);
    }


public function sppPayments()
{
    return $this->hasMany(SppPayment::class, 'admin_id');
}
}
