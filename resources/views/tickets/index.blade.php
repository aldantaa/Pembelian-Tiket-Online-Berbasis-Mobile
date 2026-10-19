@extends('layouts.master')

@section('title', 'Tiket Saya')

@section('content')
<h1>Tiket Saya</h1>

@forelse ($tickets as $t)
    <a class="card row-link"
       href="{{ route('orders.show', $t['booking_code']) }}">

        <div>
            <strong>
                {{ $t['schedule']?->kota_asal ?? '-' }}
                →
                {{ $t['schedule']?->kota_tujuan ?? '-' }}
            </strong>

            <small class="muted">
                {{ $t['schedule']?->waktu_keberangkatan?->format('d-m-Y H:i') ?? '-' }}
                · Kursi {{ $t['seats']->implode(', ') ?: '-' }}
            </small>

            <small>{{ $t['journey_status'] }}</small>
        </div>

        <div>
            <span class="badge">{{ ucfirst($t['status']) }}</span>

            <small>{{ $t['booking_code'] }}</small>

            <small>
                Rp {{ number_format($t['total'], 0, ',', '.') }}
            </small>
        </div>

    </a>
@empty
    <p class="empty">
        Belum ada tiket. Cari jadwal bus untuk mulai memesan.
    </p>

    <a href="{{ route('schedules') }}" class="btn">
        Cari Jadwal Bus
    </a>
@endforelse

@endsection
