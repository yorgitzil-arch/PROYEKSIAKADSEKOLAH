@extends('layouts.app_admin')

@section('title', 'Edit Menu')
@section('page_title', 'Edit Item Navigasi Menu')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Item Navigasi Menu</h3>
                </div>
                <div class="card-body">
                    {{-- Form ini akan digunakan untuk mengedit informasi menu --}}
                    <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Penting: Gunakan metode PUT untuk update --}}

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h5><i class="icon fas fa-ban"></i> Terjadi Kesalahan!</h5>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="name">Nama Menu <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $menu->name) }}" required>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="url">URL (Link Tujuan)</label>
                            <input type="text" name="url" id="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url', $menu->url) }}" placeholder="Contoh: /profil-sekolah atau https://google.com">
                            @error('url')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">Biarkan kosong jika ini adalah menu induk tanpa link langsung, atau jika akan memiliki sub-menu.</small>
                        </div>

                        <div class="form-group">
                            <label for="parent_id">Menu Induk (Opsional)</label>
                            <select name="parent_id" id="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                                <option value="">-- Pilih Menu Induk (Jika ini Sub-Menu) --</option>
                                @foreach($parentMenus as $parentMenu)
                                    {{-- Pastikan menu tidak bisa menjadi induk bagi dirinya sendiri atau sub-menunya --}}
                                    @if ($parentMenu->id !== $menu->id)
                                        <option value="{{ $parentMenu->id }}" {{ old('parent_id', $menu->parent_id) == $parentMenu->id ? 'selected' : '' }}>{{ $parentMenu->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('parent_id')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">Pilih jika menu ini akan menjadi sub-menu dari menu lain.</small>
                        </div>

                        <div class="form-group">
                            <label for="order">Urutan Tampilan <span class="text-danger">*</span></label>
                            <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $menu->order) }}" required min="0">
                            @error('order')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">Angka lebih kecil akan tampil lebih dulu.</small>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $menu->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Aktifkan Menu</label>
                            </div>
                            @error('is_active')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Update Menu</button>
                        <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
