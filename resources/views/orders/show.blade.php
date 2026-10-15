
<!DOCTYPE html>
<html>
<head>
    <title>Detail Pesanan - My Halte</title>
</head>
<body>
    <h1>Detail Pesanan</h1>

    <p>Kode pesanan: {{ $bookingCode }}</p>

    <p>
        Asal:
        {{ $schedule?->kota_asal ?? '-' }}
    </p>

    <p>
        Tujuan:
        {{ $schedule?->kota_tujuan ?? '-' }}
    </p>

    <p>
        Keberangkatan:
        {{ $schedule?->waktu_keberangkatan ?? '-' }}
    </p>

    <h2>Daftar Tiket</h2>

    @foreach ($bookings as $booking)
        <hr>

        <p>Kursi: {{ $booking->seat?->seat_number ?? '-' }}</p>

        <p>
            Harga:
            Rp {{ number_format($booking->price, 0, ',', '.') }}
        </p>

        <p>Status booking: {{ strtoupper($booking->status) }}</p>

        @if ($booking->ticket)
            <p>Kode tiket: {{ $booking->ticket->ticket_code }}</p>
        @else
            <p>E-ticket belum diterbitkan.</p>
        @endif
    @endforeach

    <p>
        Total:
        Rp {{ number_format($total, 0, ',', '.') }}
    </p>

    <p><a href="{{ route('orders.index') }}">Kembali ke riwayat pesanan</a></p>
</body>
</html>
