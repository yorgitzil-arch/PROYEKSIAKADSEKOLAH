<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'jurusans';

    protected $fillable = [
        'nama_jurusan',
        'kode_jurusan',
        'deskripsi',
    ];

    public static function rules($id = null)
    {
        return [
            'nama_jurusan' => 'required|string|max:255|unique:jurusans,nama_jurusan,' . $id,
            'kode_jurusan' => 'required|string|max:10|unique:jurusans,kode_jurusan,' . $id,
            'deskripsi' => 'nullable|string',
        ];
    }
}
