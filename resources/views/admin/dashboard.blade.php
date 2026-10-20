@extends('layouts.admin')
@section('title', 'Laporan & Statistik')
@push('styles')<link rel="stylesheet" href="{{ asset('css/pages/admin.css') }}">@endpush

@section('content')

<div class="section-title">
    <h1>Laporan &amp; statistik</h1>
    <div class="tabs">
        @foreach (['harian', 'bulanan'] as $p)
            <a href="{{ route('admin.dashboard', ['period' => $p]) }}" class="{{ $p === $period ? 'is-active' : '' }}">{{ ucfirst($p) }}</a>
        @endforeach
    </div>
</div>

<div class="stats">
    @foreach ($stats as $label => $value)
        <div class="card stat"><small>{{ $label }}</small><strong>{{ $value }}</strong></div>
    @endforeach
</div>

@php $max = max($chart) ?: 1; @endphp
<div class="card">
    <h2>Pendapatan {{ $period }}</h2>
    <div class="bars">
        @foreach ($chart as $label => $value)
            <div class="bars__item" title="Rp {{ number_format($value, 0, ',', '.') }}">
                <div class="bars__bar" style="height: {{ round($value / $max * 100) }}%"></div>
                <small>{{ $label }}</small>
            </div>
        @endforeach
    </div>
</div>

<div class="card table-wrap">
    <div class="section-title">
        <h2>Pendapatan per rute</h2>
        <div class="actions">
            <button class="btn btn--sm btn--ghost" onclick="window.print()">Cetak</button>
            <a class="btn btn--sm" href="{{ route('admin.export') }}">Ekspor CSV</a>
        </div>
    </div>
    <table class="table">
        <thead><tr><th>Rute</th><th class="num">Penumpang</th><th class="num">Pendapatan</th></tr></thead>
        <tbody>
        @foreach ($routes as $row)
            <tr><td>{{ $row['route'] }}</td><td class="num">{{ $row['passengers'] }}</td><td class="num">Rp {{ number_format($row['revenue'], 0, ',', '.') }}</td></tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
