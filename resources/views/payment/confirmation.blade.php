@extends('layouts.master')
@section('title', 'Konfirmasi Pembayaran')

@section('content')
@include('partials.stepper', ['active' => $payment->status === 'success' ? 5 : 4])

<div class="card">
    @if ($payment->status === 'success')
        <h2>Pembayaran berhasil</h2>
        <p class="muted">E-ticket sudah terbit dan notifikasi telah dikirim.</p>

        @foreach ($bookings as $b)
            <div class="card">
                <strong>E-ticket {{ $b->ticket->ticket_code ?? '-' }}</strong>
                <dl>
                    <dt>Penumpang</dt><dd>{{ $b->passenger->name ?? '-' }}</dd>
                    <dt>Kursi</dt><dd>{{ $b->seat->seat_number }}</dd>
                    <dt>Rute</dt><dd>{{ $schedule->kota_asal }} → {{ $schedule->kota_tujuan }}</dd>
                    <dt>Berangkat</dt><dd>{{ \Carbon\Carbon::parse($schedule->waktu_keberangkatan)->format('d M Y, H:i') }}</dd>
                </dl>
            </div>
        @endforeach

        <a class="btn" href="{{ route('schedules') }}">Kembali ke beranda</a>

    @elseif ($payment->status === 'pending')
        <h2>Menunggu konfirmasi pembayaran</h2>
        <dl>
            <dt>Kode pesanan</dt><dd>{{ $booking_code }}</dd>
            <dt>Referensi</dt><dd>{{ $payment->reference }}</dd>
            <dt>Metode</dt><dd>{{ \App\Http\Controllers\PaymentController::METHODS[$payment->method] ?? $payment->method }}</dd>
            <dt>Total</dt><dd>Rp {{ number_format($payment->amount, 0, ',', '.') }}</dd>
        </dl>
        <p class="muted">Halaman ini akan menampilkan e-ticket setelah pembayaran dikonfirmasi.</p>

        @if (app()->environment('local'))
            <form method="POST" action="{{ route('payment.simulate', $booking_code) }}">
                @csrf
                <button class="btn">Simulasikan pembayaran berhasil (khusus local)</button>
            </form>
        @endif

    @else
        <h2>Pembayaran tidak berhasil</h2>
        <p class="empty">Status: {{ $payment->status }}. Silakan pesan ulang.</p>
        <a class="btn" href="{{ route('schedules') }}">Cari jadwal lagi</a>
    @endif
</div>
@endsection
