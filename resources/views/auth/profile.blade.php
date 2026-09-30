@extends('layouts.master')
@section('title', 'Profil Saya')
@push('styles')<link rel="stylesheet" href="{{ asset('css/pages/auth.css') }}">@endpush

@section('content')
<section class="card auth">
    <h1>Profil saya</h1>
    <p class="muted">Perbarui data akun Anda.</p>
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @include('partials.input', ['label' => 'Nama lengkap', 'name' => 'name', 'value' => $user['name']])
        @include('partials.input', ['label' => 'Email', 'name' => 'email', 'type' => 'email', 'value' => $user['email']])
        @include('partials.input', ['label' => 'Nomor telepon', 'name' => 'phone', 'type' => 'tel', 'value' => $user['phone'] ?? ''])
        <button class="btn btn--block">Simpan perubahan</button>
    </form>
</section>
@endsection
