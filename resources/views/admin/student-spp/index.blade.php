@extends('layouts.app_admin')

@section('title', 'Manajemen Tipe SPP Siswa')
@section('page_title', 'Manajemen Tipe SPP Siswa')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card rounded-lg">
                <div class="card-header">
                    <h3 class="card-title">Daftar Siswa</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jurusan</th>
                                    <th>Tipe Pembayaran SPP</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswas as $siswa)
                                    <tr>
                                        <td>{{ $siswa->nisn }}</td>
                                        <td>{{ $siswa->name }}</td>
                                        <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                                        <td>{{ $siswa->jurusan->nama_jurusan ?? '-' }}</td>
                                        <td>
                                            <form action="{{ route('admin.student-spp.updateSppType', $siswa) }}" method="POST" id="form-{{ $siswa->id }}">
                                                @csrf
                                                <div class="form-group mb-0">
                                                    <select name="spp_type_id" class="form-control form-control-sm">
                                                        <option value="">-- Pilih Tipe --</option>
                                                        @foreach($sppTypes as $type)
                                                            <option value="{{ $type->id }}" {{ $siswa->spp_type_id == $type->id ? 'selected' : '' }}>
                                                                {{ $type->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </form>
                                        </td>
                                        <td class="text-right">
                                            <button type="submit" form="form-{{ $siswa->id }}" class="btn btn-sm btn-primary rounded-lg">Simpan</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $siswas->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection