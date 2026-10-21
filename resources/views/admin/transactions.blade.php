@extends('layouts.admin')
@section('title', 'Riwayat Transaksi')
@push('styles')<link rel="stylesheet" href="{{ asset('css/pages/admin.css') }}">@endpush

@section('content')
<h1>Riwayat transaksi</h1>

<div class="card table-wrap">
    <table class="table">
        <thead>
            <tr>
                <th>Kode Booking</th>
                <th>Penumpang</th>
                <th>Metode Pembayaran</th>
                <th class="num">Total</th>
                <th>Status Pembayaran</th>
                <th>Status Booking</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $t)
                <tr>
                    <td>{{ $t->booking_code ?? '-' }}</td>
                    <td>{{ $t->user_name ?? 'Pengguna tidak ditemukan' }}</td>
                    <td>{{ $t->method ?? '-' }}</td>
                    <td class="num">
                        Rp {{ number_format((float) ($t->amount ?? 0), 0, ',', '.') }}
                    </td>
                    <td>{{ ucfirst($t->payment_status ?? 'unknown') }}</td>
                    <td>{{ ucfirst($t->booking_status ?? 'unknown') }}</td>
                    <td>
                        {{ $t->created_at
                            ? \Carbon\Carbon::parse($t->created_at)->format('d M Y H:i')
                            : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty">Belum ada transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $transactions->links() }}
</div>
@endsection
