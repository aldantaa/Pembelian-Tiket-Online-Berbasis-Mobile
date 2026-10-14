@extends('layouts.master')
@section('title', 'Lupa Password')
@push('styles')<link rel="stylesheet" href="{{ asset('css/pages/auth.css') }}">@endpush

@section('content')
<section class="card auth">
    <h1>Atur ulang password</h1>
    <p class="muted">Masukkan email akun Anda dan password baru.</p>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @include('partials.input', ['label' => 'Email', 'name' => 'email', 'type' => 'email', 'placeholder' => 'nama@email.com'])
        @include('partials.input', ['label' => 'Password baru', 'name' => 'password', 'type' => 'password'])
        @include('partials.input', ['label' => 'Konfirmasi password baru', 'name' => 'password_confirmation', 'type' => 'password'])
        <button class="btn btn--block">Simpan password baru</button>
    </form>
    <p class="auth__alt"><a href="{{ route('login') }}">Kembali ke halaman masuk</a></p>
</section>
@endsection