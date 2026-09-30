@extends('layouts.master')
@section('title', 'Daftar')
@push('styles')<link rel="stylesheet" href="{{ asset('css/pages/auth.css') }}">@endpush

@section('content')
<section class="card auth">
    <h1>Daftar</h1>
    <p class="muted">Buat akun baru untuk mulai memesan tiket.</p>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        @include('partials.input', ['label' => 'Nama lengkap', 'name' => 'nama_lengkap', 'placeholder' => 'Nama lengkap Anda'])
        @include('partials.input', ['label' => 'Email', 'name' => 'email', 'type' => 'email', 'placeholder' => 'nama@email.com'])
        @include('partials.input', ['label' => 'Nomor telepon', 'name' => 'no_hp', 'type' => 'tel', 'placeholder' => '08123456789'])
        @include('partials.input', ['label' => 'Password', 'name' => 'password', 'type' => 'password'])
        @include('partials.input', ['label' => 'Konfirmasi password', 'name' => 'password_confirmation', 'type' => 'password'])
        <button class="btn btn--block">Daftar</button>
    </form>
    <p class="auth__alt">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
</section>
@endsection
