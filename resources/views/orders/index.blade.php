
<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Pesanan - My Halte</title>
</head>
<body>
    <h1>Riwayat Pesanan</h1>

    @if ($orders->isEmpty())
        <p>Belum ada pesanan tiket.</p>
    @else
        @foreach ($orders as $order)
            <hr>

            <h2>Pesanan {{ $order['booking_code'] }}</h2>

            <p>
                Asal:
                {{ $order['schedule']?->kota_asal ?? '-' }}
            </p>

            <p>
                Tujuan:
                {{ $order['schedule']?->kota_tujuan ?? '-' }}
            </p>

            <p>
                Keberangkatan:
                {{ $order['schedule']?->waktu_keberangkatan ?? '-' }}
            </p>

            <p>
                Kursi:
                {{ $order['seats']->isEmpty()
                    ? '-'
                    : $order['seats']->implode(', ') }}
            </p>

            <p>
                Total:
                Rp {{ number_format($order['total'], 0, ',', '.') }}
            </p>

            <p>
                Status pembayaran:
                {{ strtoupper($order['status']) }}
            </p>

            <p>
                Status perjalanan:
                {{ $order['journey_status'] }}
            </p>

            @if ($order['is_active'])
                <p>Tiket aktif</p>
            @endif

            <p>
                <a href="{{ route('orders.show', [
                    'booking_code' => $order['booking_code']
                ]) }}">
                    Lihat detail pesanan
                </a>
            </p>
        @endforeach
    @endif

    <p><a href="{{ route('schedules') }}">Cari jadwal bus</a></p>
</body>
</html>
