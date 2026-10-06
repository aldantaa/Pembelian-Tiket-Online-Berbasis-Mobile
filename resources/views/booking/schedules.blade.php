@extends('layouts.master')
@section('title', 'Cari Tiket')

@section('content')
@include('partials.stepper', ['active' => 1])

<form method="GET" action="{{ route('schedules') }}" class="card search">
    @include('partials.input', ['label' => 'Keberangkatan', 'name' => 'from', 'value' => $query['from'] ?? 'Jakarta'])
    @include('partials.input', ['label' => 'Tujuan', 'name' => 'to', 'value' => $query['to'] ?? 'Surabaya'])
    @include('partials.input', ['label' => 'Tanggal keberangkatan', 'name' => 'date', 'type' => 'date', 'value' => $query['date'] ?? ''])
    <button class="btn">Cari bus</button>
</form>

@if ($buses)
    <div class="section-title">
        <h2>{{ count($buses) }} bus tersedia</h2>
        <span class="muted">{{ $query['from'] }} → {{ $query['to'] }}</span>
    </div>
    <div class="grid">
        @foreach ($buses as $bus)
            @include('partials.bus-card', ['bus' => $bus])
        @endforeach
    </div>
@else
    <p class="empty">Isi kota asal, tujuan, dan tanggal untuk melihat bus yang tersedia.</p>
@endif
@endsection
