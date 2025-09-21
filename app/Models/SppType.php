<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'duration_in_months',
    ];

    public function sppPayments()
    {
        return $this->hasMany(SppPayment::class);
    }
}
