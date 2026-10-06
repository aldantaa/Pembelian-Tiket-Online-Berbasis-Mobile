@extends('layouts.master')
@section('title', 'Masuk')
@push('styles')<link rel="stylesheet" href="{{ asset('css/pages/auth.css') }}">@endpush

@section('content')
<section class="card auth">
    <h1>Masuk</h1>
    <p class="muted">Masukkan email dan password Anda.</p>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        @include('partials.input', ['label' => 'Email', 'name' => 'email', 'type' => 'email', 'placeholder' => 'nama@email.com'])
        @include('partials.input', ['label' => 'Password', 'name' => 'password', 'type' => 'password'])
        <a href="{{ route('password.request') }}" class="auth__forgot">Lupa password?</a>
        <button class="btn btn--block">Masuk</button>
    </form>
    <p class="auth__alt">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
</section>
@endsection
