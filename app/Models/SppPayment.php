<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'admin_id',
        'spp_type_id',
        'tahun_ajaran_id',
        'semester_id',
        'amount',
        'status',
        'payment_date',
        'transaction_id',
        'notes',
        'proof_path',
    ];


    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }


    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function sppType()
    {
        return $this->belongsTo(SppType::class);
    }
}
