@extends('layouts.master')
@section('title', 'Pembayaran')

@section('content')
@include('partials.stepper', ['active' => 4])

<div class="two-col">
    <div class="card">
        <h2>Metode pembayaran</h2>

        @if ($status === 'pending')
            <p class="muted">Selesaikan pembayaran sebelum {{ $expiresAt?->format('d M Y, H:i') }}.</p>

            <form method="POST" action="{{ route('payment.pay', $booking_code) }}">
                @csrf
                <div class="method-list">
                    @foreach ($methods as $value => $label)
                        <label class="method">
                            <input type="radio" name="method" value="{{ $value }}"
                                {{ old('method', array_key_first($methods)) === $value ? 'checked' : '' }}>
                            <span><b>{{ $label }}</b></span>
                        </label>
                    @endforeach
                </div>
                <button class="btn btn--block">Bayar sekarang</button>
            </form>
        @else
            <p class="empty">Pesanan ini sudah tidak bisa dibayar (status: {{ $status }}).</p>
            <a class="btn" href="{{ route('schedules') }}">Cari jadwal lagi</a>
        @endif
    </div>

    <aside class="card summary">
        <h3>Ringkasan</h3>
        <dl>
            <dt>Kode pesanan</dt><dd>{{ $booking_code }}</dd>
            <dt>Rute</dt><dd>{{ $schedule->kota_asal }} → {{ $schedule->kota_tujuan }}</dd>
            <dt>Berangkat</dt><dd>{{ \Carbon\Carbon::parse($schedule->waktu_keberangkatan)->format('d M Y, H:i') }}</dd>
            @foreach ($bookings as $b)
                <dt>Kursi {{ $b->seat->seat_number }}</dt>
                <dd>{{ $b->passenger->name ?? '-' }}</dd>
            @endforeach
        </dl>
        <div class="summary__total">
            <span>Total</span>
            <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
        </div>
    </aside>
</div>
@endsection
