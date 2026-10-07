@extends('layouts.master')
@section('title', 'Cari Tiket')

@section('content')
@include('partials.stepper', ['active' => 1])

<form method="GET" action="{{ route('schedules') }}" class="card search">
    <!-- Mengganti $query['...'] dengan request('...') agar tidak error jika controller tidak mengirim $query -->
    @include('partials.input', ['label' => 'Keberangkatan', 'name' => 'from', 'value' => request('from', 'Madiun')])
    @include('partials.input', ['label' => 'Tujuan', 'name' => 'to', 'value' => request('to', 'Surabaya')])
    @include('partials.input', ['label' => 'Tanggal keberangkatan', 'name' => 'date', 'type' => 'date', 'value' => request('date')])
    <button class="btn">Cari bus</button>
</form>

<!-- Mengganti pengecekan $buses menjadi $schedules -->
@if (isset($schedules) && count($schedules) > 0)
    <div class="section-title">
        <!-- Mengubah $buses menjadi $schedules -->
        <h2>{{ count($schedules) }} jadwal tersedia</h2>
        <span class="muted">{{ request('from') ?? 'Semua Asal' }} → {{ request('to') ?? 'Semua Tujuan' }}</span>
    </div>
    <div class="grid">
        <!-- Melakukan looping pada variabel $schedules -->
        @foreach ($schedules as $schedule)
            <!-- Mengirimkan data jadwal (berubah dari 'bus' ke 'schedule') ke partials -->
            @include('partials.bus-card', ['schedule' => $schedule])
        @endforeach
    </div>
@else
    <p class="empty">Tidak ada jadwal bus yang tersedia.</p>
@endif
@endsection
