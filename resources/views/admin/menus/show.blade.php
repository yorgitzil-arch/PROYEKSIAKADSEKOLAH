@extends('layouts.app_admin')

@section('title', 'Detail Menu')
@section('page_title', 'Detail Menu')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Item Navigasi Menu: {{ $menu->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini? Semua sub-menu akan ikut terhapus!')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Nama Menu:</dt>
                        <dd class="col-sm-8">{{ $menu->name }}</dd>

                        <dt class="col-sm-4">URL:</dt>
                        <dd class="col-sm-8">{{ $menu->url ?? '-' }}</dd>

                        <dt class="col-sm-4">Menu Induk:</dt>
                        <dd class="col-sm-8">{{ $menu->parent->name ?? '- (Menu Utama)' }}</dd>

                        <dt class="col-sm-4">Urutan Tampilan:</dt>
                        <dd class="col-sm-8">{{ $menu->order }}</dd>

                        <dt class="col-sm-4">Status:</dt>
                        <dd class="col-sm-8">
                            @if($menu->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Nonaktif</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Dibuat Pada:</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($menu->created_at)->format('d F Y H:i') }}</dd>

                        <dt class="col-sm-4">Terakhir Diperbarui:</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($menu->updated_at)->format('d F Y H:i') }}</dd>
                    </dl>

                    @if($menu->children->isNotEmpty())
                        <h5 class="mt-4">Sub-Menu:</h5>
                        <ul>
                            @foreach($menu->children as $child)
                                <li>{{ $child->name }} (URL: {{ $child->url ?? '-' }}, Urutan: {{ $child->order }}, Status: {{ $child->is_active ? 'Aktif' : 'Nonaktif' }})</li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Kembali ke Daftar Menu</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
