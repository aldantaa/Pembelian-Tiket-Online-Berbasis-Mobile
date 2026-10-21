@extends('layouts.master')
@section('title', 'Tiket Saya')

@section('content')
<h1>Tiket saya</h1>
@forelse ($tickets as $t)
    <a class="card row-link" href="{{ route('ticket') }}">
        <div><strong>{{ $t['route'] }}</strong><small class="muted">{{ $t['date'] }} · Kursi {{ $t['seats'] }}</small></div>
        <div><span class="badge">{{ $t['status'] }}</span><small>{{ $t['code'] }}</small></div>
    </a>
@empty
    <p class="empty">Belum ada tiket. Cari jadwal bus untuk mulai memesan.</p>
@endforelse
@endsection
