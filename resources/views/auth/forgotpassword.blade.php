@extends('layouts.master')

@section('title', 'Lupa Password')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/auth.css') }}">
@endpush

@section('content')
<section class="card auth">
    <h1>Atur ulang password</h1>
    <p class="muted">
        Masukkan email akun Anda. Jika email terdaftar,
        kami akan mengirimkan tautan untuk mengatur ulang password.
    </p>

    @if (session('status'))
        <div class="alert alert--success">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert--error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        @include('partials.input', [
            'label' => 'Email',
            'name' => 'email',
            'type' => 'email',
            'placeholder' => 'nama@email.com'
        ])

        <button type="submit" class="btn btn--block">
            Kirim tautan reset password
        </button>
    </form>

    <p class="auth__alt">
        <a href="{{ route('login') }}">Kembali ke halaman masuk</a>
    </p>
</section>
@endsection
