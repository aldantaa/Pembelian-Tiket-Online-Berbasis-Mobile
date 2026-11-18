
@extends('layouts.admin')

@section('title', 'Notifikasi Admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/adminalerts.css') }}">
@endpush

@section('content')
<div class="page-heading">
    <div>
        <h1>Notifikasi Admin</h1>
        <p>Informasi pesanan yang perlu dipantau oleh administrator.</p>
    </div>
</div>

<div class="stats">
    <div class="card stat alert-stat">
        <small>Menunggu pembayaran</small>
        <strong>{{ number_format($pendingCount) }}</strong>
    </div>

    <div class="card stat alert-stat">
        <small>Pesanan kedaluwarsa</small>
        <strong>{{ number_format($expiredCount) }}</strong>
    </div>

    <div class="card stat alert-stat">
        <small>Pesanan dibayar</small>
        <strong>{{ number_format($paidCount) }}</strong>
    </div>
</div>

<nav class="alert-filters" aria-label="Filter notifikasi">
    <a href="{{ route('admin.alerts') }}"
       class="{{ !request()->filled('type') ? 'active' : '' }}">
        Perlu perhatian
    </a>

    <a href="{{ route('admin.alerts', ['type' => 'pending']) }}"
       class="{{ request('type') === 'pending' ? 'active' : '' }}">
        Menunggu
    </a>

    <a href="{{ route('admin.alerts', ['type' => 'expired']) }}"
       class="{{ request('type') === 'expired' ? 'active' : '' }}">
        Kedaluwarsa
    </a>

    <a href="{{ route('admin.alerts', ['type' => 'paid']) }}"
       class="{{ request('type') === 'paid' ? 'active' : '' }}">
        Dibayar
    </a>
</nav>

<div class="card alert-list">
    @forelse ($alerts as $alert)
        @php
            $status = strtolower($alert->status ?? '');

            $statusClass = $status === 'paid'
                ? 'paid'
                : ($status === 'expired' ? 'expired' : '');

            $icon = $status === 'paid'
                ? '✓'
                : ($status === 'expired' ? '!' : '⌛');
        @endphp

        <article class="alert-item">
            <div class="alert-icon" aria-hidden="true">
                {{ $icon }}
            </div>

            <div class="alert-body">
                <h3>
                    @if ($status === 'paid')
                        Pembayaran berhasil
                    @elseif ($status === 'expired')
                        Pesanan kedaluwarsa
                    @else
                        Menunggu pembayaran
                    @endif

                    <span class="alert-status {{ $statusClass }}">
                        {{ ucfirst($status ?: 'unknown') }}
                    </span>
                </h3>

                <p>
                    Pesanan <strong>{{ $alert->booking_code ?? '-' }}</strong>
                    oleh {{ $alert->user_name ?? 'Pengguna tidak ditemukan' }}.
                </p>

                <p>
                    Rute: {{ $alert->kota_asal ?? '-' }}
                    → {{ $alert->kota_tujuan ?? '-' }}.
                </p>

                <div class="alert-meta">
                    {{ $alert->created_at
                        ? \Carbon\Carbon::parse($alert->created_at)->format('d M Y, H:i')
                        : '-' }}

                    @if (!empty($alert->user_email))
                        · {{ $alert->user_email }}
                    @endif

                    @if (!empty($alert->price))
                        · Rp {{ number_format((float) $alert->price, 0, ',', '.') }}
                    @endif
                </div>
            </div>
        </article>

    @empty
        <div class="alert-empty">
            <div class="alert-empty__icon" aria-hidden="true">🔔</div>
            <h2>Belum ada notifikasi</h2>
            <p>Tidak ada pesanan pada kategori ini untuk saat ini.</p>
        </div>
    @endforelse
</div>

<div class="mt-3">
    {{ $alerts->links() }}
</div>
@endsection
