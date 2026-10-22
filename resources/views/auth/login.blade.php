@extends('layouts.master')
@section('title', 'Masuk')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/auth.css') }}">
@endpush

@section('content')
<section class="card auth">
    <h1>Masuk</h1>
    <p class="muted">Masukkan email atau nomor telepon dan password Anda.</p>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="login">Email atau Nomor Telepon</label>
            <input
                type="text"
                id="login"
                name="login"
                value="{{ old('login') }}"
                placeholder="nama@email.com atau nomor telepon"
                autocomplete="username"
                required
            >

            @error('login')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Masukkan password"
                autocomplete="current-password"
                required
            >

            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <a href="{{ route('password.request') }}" class="auth__forgot">
            Lupa password?
        </a>

        <button type="submit" class="btn btn--block">Masuk</button>
    </form>

    <p class="auth__alt">
        Belum punya akun?
        <a href="{{ route('register') }}">Daftar sekarang</a>
    </p>
</section>
@endsection
