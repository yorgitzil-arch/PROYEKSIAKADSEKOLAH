@extends('layouts.app_siswa') {{-- Pastikan Anda memiliki layout untuk siswa, misal resources/views/layouts/app_siswa.blade.php --}}

@section('title', 'Nilai Saya')
@section('page_title', 'Daftar Nilai Anda')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Nilai Anda, {{ $siswa->name }}</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-valign-middle">
                        <thead>
                        <tr>
                            <th style="width: 50px">#</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Guru Pengampu</th>
                            <th>Nilai Pengetahuan</th> {{-- Ubah nama kolom --}}
                            <th>Nilai Keterampilan</th> {{-- Tambah kolom --}}
                            <th>Deskripsi Pengetahuan</th> {{-- Ubah nama kolom --}}
                            <th>Deskripsi Keterampilan</th> {{-- Tambah kolom --}}
                            <th>Tanggal Rekap</th> {{-- Ubah nama kolom --}}
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($grades as $grade)
                            <tr>
                                <td>{{ $loop->iteration + ($grades->currentPage() - 1) * $grades->perPage() }}</td>
                                {{-- KOREKSI: Akses langsung relasi dari RekapNilaiMapel --}}
                                <td>{{ $grade->mataPelajaran->nama_mapel ?? 'N/A' }}</td>
                                <td>{{ $grade->kelas->nama_kelas ?? 'N/A' }}</td>
                                <td>{{ $grade->guruPengampu->name ?? 'N/A' }}</td>
                                {{-- KOREKSI: Tampilkan nilai pengetahuan dan keterampilan --}}
                                <td><span class="badge {{ ($grade->nilai_pengetahuan_angka ?? 0) >= 75 ? 'bg-success' : 'bg-danger' }}">{{ $grade->nilai_pengetahuan_angka ?? '-' }}</span></td>
                                <td><span class="badge {{ ($grade->nilai_keterampilan_angka ?? 0) >= 75 ? 'bg-success' : 'bg-danger' }}">{{ $grade->nilai_keterampilan_angka ?? '-' }}</span></td>
                                {{-- KOREKSI: Tampilkan deskripsi pengetahuan dan keterampilan --}}
                                <td>{{ $grade->deskripsi_pengetahuan ?? '-' }}</td>
                                <td>{{ $grade->deskripsi_keterampilan ?? '-' }}</td>
                                {{-- KOREKSI: Tanggal input menjadi tanggal rekap --}}
                                <td>{{ $grade->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Anda belum memiliki data nilai.</td> {{-- Sesuaikan colspan --}}
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $grades->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection
