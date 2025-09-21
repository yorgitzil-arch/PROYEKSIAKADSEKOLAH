<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Guru; // Panggil model Guru
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Guru::create([
            'nip' => '001102', 
            'name' => 'Yordinatal',
            'email' => 'yordinat@mail.ac.id',
            'password' => Hash::make('password'),
        ]);
    }
}
