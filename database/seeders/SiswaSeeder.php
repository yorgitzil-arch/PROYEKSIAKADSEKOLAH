<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Siswa; 
use Illuminate\Support\Facades\Hash;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Siswa::create([
            'nis' => '05122004',
            'nisn'=> '200412',
            'name' => 'Yordinatal Putra Efriel Ziliwu',
            'email' => 'yordi@mail.ac.id',
            'password' => Hash::make('password'),
        ]);
    }
}
