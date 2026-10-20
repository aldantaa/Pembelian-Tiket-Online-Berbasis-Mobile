@extends('layouts.admin')
@section('title', 'Riwayat Transaksi')
@push('styles')<link rel="stylesheet" href="{{ asset('css/pages/admin.css') }}">@endpush

@section('content')
<h1>Riwayat transaksi</h1>

<div class="card table-wrap">
    <table class="table">
        <thead><tr><th>Kode</th><th>Penumpang</th><th>Rute</th><th>Kursi</th><th class="num">Total</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse ($transactions as $t)
            <tr>
                <td class="text-primary">{{ $t['code'] }}</td><td>{{ $t['name'] }}</td><td>{{ $t['route'] }}</td><td>{{ $t['seats'] }}</td>
                <td class="num">Rp {{ number_format($t['total'], 0, ',', '.') }}</td>
                <td>@include('partials.status-badge', ['status' => $t['status']])</td>
                <td>@include('partials.deletebutton', ['action' => route('admin.transactions.delete', $t['code']), 'confirm' => 'Hapus transaksi ini?'])</td>
            </tr>
        @empty
            <tr><td colspan="7" class="empty">Belum ada transaksi.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
