@extends('layouts.app_guru') {{-- PASTIKAN INI MENGARAH KE LAYOUT GURU YANG BENAR --}}

@section('title', 'Dashboard Guru')
@section('page_title', 'Dashboard Guru')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    {{-- Ganti $user->name menjadi $guru->name --}}
                    <h5 class="card-title">Selamat Datang, Bapak/Ibu Guru {{ $guru->name }}!</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Ini adalah halaman dashboard untuk Portal Guru.</p>
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
