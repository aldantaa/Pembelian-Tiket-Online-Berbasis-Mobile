@extends('layouts.master')

@section('title', 'Password Baru')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/auth.css') }}">
@endpush

@section('content')
<section class="card auth">
    <h1>Buat Password Baru</h1>
    <p class="muted">
        Masukkan password baru untuk akun Anda.
    </p>

    @if ($errors->any())
        <div class="alert alert--error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', $email) }}"
                required
            >
        </div>

        <div class="form-group">
            <label for="password">Password Baru</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                minlength="8"
                autocomplete="new-password"
            >
        </div>

        <div class="form-group">
            <label for="password_confirmation">
                Konfirmasi Password Baru
            </label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
                minlength="8"
                autocomplete="new-password"
            >
        </div>

        <button type="submit" class="btn btn--block">
            Simpan Password Baru
        </button>
    </form>

    <p class="auth__alt">
        <a href="{{ route('login') }}">Kembali ke halaman masuk</a>
    </p>
</section>
@endsection
