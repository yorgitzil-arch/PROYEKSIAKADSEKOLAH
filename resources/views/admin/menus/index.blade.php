@extends('layouts.app_admin')

@section('title', 'Manajemen Navigasi Menu')
@section('page_title', 'Manajemen Navigasi Menu')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Item Navigasi Menu</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.menus.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Menu Baru
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Pesan Sukses/Error --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($menus->isEmpty())
                        <div class="alert alert-info text-center">
                            Belum ada item menu yang ditambahkan.
                        </div>
                    @else
                        <table id="menusTable" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Menu</th>
                                <th>URL</th>
                                <th>Menu Induk</th>
                                <th>Urutan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($menus->whereNull('parent_id')->sortBy('order') as $menu)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $menu->name }}</strong></td>
                                    <td>{{ $menu->url ?? '-' }}</td>
                                    <td>- (Menu Utama)</td>
                                    <td>{{ $menu->order }}</td>
                                    <td>
                                        @if($menu->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn btn-sm btn-warning" title="Edit Menu">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini? Semua sub-menu akan ikut terhapus!')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                {{-- Tampilkan sub-menu --}}
                                @foreach($menu->children as $childMenu)
                                    <tr>
                                        <td></td>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $childMenu->name }}</td>
                                        <td>{{ $childMenu->url ?? '-' }}</td>
                                        <td>{{ $menu->name }}</td>
                                        <td>{{ $childMenu->order }}</td>
                                        <td>
                                            @if($childMenu->is_active)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.menus.edit', $childMenu->id) }}" class="btn btn-sm btn-warning" title="Edit Sub-Menu">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.menus.destroy', $childMenu->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus sub-menu ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
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
