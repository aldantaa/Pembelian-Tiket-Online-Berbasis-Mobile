@extends('layouts.master')
@section('title', 'Cari Tiket')

@section('content')
@include('partials.stepper', ['active' => 1])

<form method="GET" action="{{ route('schedules') }}" class="card search">
    @include('partials.input', ['label' => 'Keberangkatan', 'name' => 'from', 'value' => request('from', '')])
    @include('partials.input', ['label' => 'Tujuan', 'name' => 'to', 'value' => request('to', '')])
    @include('partials.input', ['label' => 'Tanggal keberangkatan', 'name' => 'date', 'type' => 'date', 'value' => request('date', '')])
    <button class="btn">Cari bus</button>
</form>


@if ($schedules->count() > 0)

    <div class="section-title">
        <h2>
            @if (request()->filled('from') ||
                 request()->filled('to') ||
                 request()->filled('date'))
                {{ $schedules->count() }} bus tersedia
            @else
                Rekomendasi Jadwal Bus
            @endif
        </h2>

        @if (request()->filled('from') || request()->filled('to'))
            <span class="muted">
                {{ request('from') ?: 'Semua kota' }}
                →
                {{ request('to') ?: 'Semua tujuan' }}
            </span>
        @endif
    </div>

    <div class="grid">
        @foreach ($schedules as $bus)
            @include('partials.buscard', ['bus' => $bus])
        @endforeach
    </div>

@else
    <p class="empty">
        Tidak ada jadwal bus yang sesuai dengan pencarian.
    </p>
@endif

@endsection
