@extends('layouts.app_admin')

@section('title', 'Tambah Menu Baru')
@section('page_title', 'Tambah Menu Baru')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Item Navigasi Menu</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menus.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Menu <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="url">URL (Link Tujuan)</label>
                            <input type="text" name="url" id="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url') }}" placeholder="Contoh: /profil-sekolah atau https://google.com">
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
                                    <option value="{{ $parentMenu->id }}" {{ old('parent_id') == $parentMenu->id ? 'selected' : '' }}>{{ $parentMenu->name }}</option>
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
                            <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', 0) }}" required min="0">
                            @error('order')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">Angka lebih kecil akan tampil lebih dulu.</small>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Aktifkan Menu</label>
                            </div>
                            @error('is_active')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Menu</button>
                        <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
