@extends('layouts.app_guru')

@section('title', 'Apresiasi')
@section('page_title', 'Apresiasi')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Apresiasi yang Anda Terima</h3>
                </div>
                <div class="card-body">
                    @if($appreciations->isEmpty())
                        <div class="alert alert-info text-center">
                            Anda belum menerima apresiasi apa pun dari Admin.
                        </div>
                    @else
                        <table id="appreciationsTable" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Judul Apresiasi</th>
                                <th>Pesan</th>
                                <th>Kategori</th>
                                <th>Dari Admin</th>
                                <th>Tanggal Diterima</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($appreciations as $appreciation)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $appreciation->title }}</td>
                                    <td>{{ Str::limit($appreciation->message, 100) }}</td>
                                    <td>
                                        @php
                                            $badgeClass = '';
                                            switch($appreciation->category) {
                                                case 'sangat_luar_biasa': $badgeClass = 'badge-success'; break;
                                                case 'baik': $badgeClass = 'badge-primary'; break;
                                                case 'cukup': $badgeClass = 'badge-info'; break;
                                                case 'kurang': $badgeClass = 'badge-danger'; break;
                                                default: $badgeClass = 'badge-secondary'; break;
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                                {{ ucfirst(str_replace('_', ' ', $appreciation->category)) }}
                                            </span>
                                    </td>
                                    <td>{{ $appreciation->admin->name ?? 'Admin Tidak Dikenal' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appreciation->created_at)->format('d F Y H:i') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Anda bisa menambahkan DataTables di sini jika diperlukan --}}
@endpush
