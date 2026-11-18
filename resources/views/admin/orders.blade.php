
@extends('layouts.admin')

@section('title', 'Riwayat Pesanan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/adminorders.css') }}">
@endpush

@section('content')
<div class="page-heading">
    <div>
        <h1>Riwayat Pesanan</h1>
        <p>Pantau seluruh pemesanan tiket yang dilakukan pengguna.</p>
    </div>
</div>

<div class="stats">
    <div class="card stat order-stat">
        <small>Total pesanan</small>
        <strong>{{ number_format($totalOrders) }}</strong>
    </div>

    <div class="card stat order-stat">
        <small>Sudah dibayar</small>
        <strong>{{ number_format($paidOrders) }}</strong>
    </div>

    <div class="card stat order-stat">
        <small>Menunggu pembayaran</small>
        <strong>{{ number_format($pendingOrders) }}</strong>
    </div>

    <div class="card stat order-stat">
        <small>Kedaluwarsa</small>
        <strong>{{ number_format($expiredOrders) }}</strong>
    </div>
</div>

<form method="GET" action="{{ route('admin.orders') }}" class="order-search">
    <div class="order-search__field">
        <label for="search">Cari pesanan</label>
        <input
            id="search"
            type="search"
            name="search"
            value="{{ request('search') }}"
            placeholder="Kode booking / nama / email"
        >
    </div>

    <div class="order-search__field">
        <label for="status">Status pesanan</label>
        <select id="status" name="status">
            <option value="">Semua status</option>
            <option value="pending" @selected(request('status') === 'pending')>
                Menunggu
            </option>
            <option value="paid" @selected(request('status') === 'paid')>
                Dibayar
            </option>
            <option value="expired" @selected(request('status') === 'expired')>
                Kedaluwarsa
            </option>
        </select>
    </div>

    <button type="submit" class="btn">Terapkan</button>
    <a href="{{ route('admin.orders') }}" class="btn btn--ghost">Reset</a>
</form>

<div class="card table-wrap">
    <table class="table">
        <thead>
            <tr>
                <th>Kode Booking</th>
                <th>Pemesan</th>
                <th>Rute &amp; Keberangkatan</th>
                <th>Kursi</th>
                <th class="num">Total</th>
                <th>Pembayaran</th>
                <th>Status Pesanan</th>
                <th>Tanggal Pesan</th>
            </tr>
        </thead>

        <tbody>
        @forelse ($orders as $order)
            @php
                $bookingStatus = strtolower($order->status ?? '');

                $statusClass = in_array(
                    $bookingStatus,
                    ['paid', 'success'],
                    true
                )
                    ? 'status-paid'
                    : (in_array(
                        $bookingStatus,
                        ['expired', 'cancelled', 'failed'],
                        true
                    ) ? 'status-expired' : 'status-pending');
            @endphp

            <tr>
                <td>
                    <span class="order-code">
                        {{ $order->booking_code ?? '-' }}
                    </span>
                </td>

                <td>
                    {{ $order->user_name ?? 'Pengguna tidak ditemukan' }}
                    <br>
                    <span class="muted">
                        {{ $order->user_email ?? '-' }}
                    </span>
                </td>

                <td>
                    {{ $order->kota_asal ?? '-' }}
                    →
                    {{ $order->kota_tujuan ?? '-' }}
                    <br>
                    <span class="muted">
                        {{ $order->waktu_keberangkatan
                            ? \Carbon\Carbon::parse($order->waktu_keberangkatan)->format('d M Y, H:i')
                            : '-' }}
                    </span>
                </td>

                <td>{{ $order->seat_id ?? '-' }}</td>

                <td class="num">
                    Rp {{ number_format((float) ($order->price ?? 0), 0, ',', '.') }}
                </td>

                <td>
                    {{ $order->payment_method ? ucfirst($order->payment_method) : '-' }}
                    <br>
                    <span class="muted">
                        {{ $order->payment_status
                            ? ucfirst($order->payment_status)
                            : 'Belum ada data' }}
                    </span>
                </td>

                <td>
                    <span class="status-pill {{ $statusClass }}">
                        {{ $order->status ? ucfirst($order->status) : 'Tidak diketahui' }}
                    </span>
                </td>

                <td>
                    {{ $order->created_at
                        ? \Carbon\Carbon::parse($order->created_at)->format('d M Y H:i')
                        : '-' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="empty">
                    Belum ada pesanan yang sesuai dengan pencarian.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $orders->links() }}
</div>
@endsection
