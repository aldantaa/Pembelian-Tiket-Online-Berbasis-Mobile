@extends('layouts.master')
@section('title', 'Pilih Kursi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/seats.css') }}">
@endpush

@section('content')
    @include('partials.stepper', ['active' => 2])

<form method="GET" action="{{ route('passenger') }}" class="seat-page" data-price="{{ $bus['price'] }}">
    <input type="hidden" name="bus" value="{{ $bus['id'] }}">
    <input type="hidden" name="seats" id="seatsInput">
    <input type="hidden" name="from" value="{{ $query['from'] ?? '' }}">
    <input type="hidden" name="to" value="{{ $query['to'] ?? '' }}">
    <input type="hidden" name="date" value="{{ $query['date'] ?? '' }}">

        <div class="section-title">
            <h2>Pilih Kursi Anda</h2>
            <span class="muted">{{ $schedule->origin }} → {{ $schedule->destination }}</span>
        </div>

        <!-- Render Daftar Kursi -->
        <div class="seat-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin: 20px 0;">
            @if(isset($seats) && count($seats) > 0)
                @foreach ($seats as $seat)
                    <!-- Logika sederhana: jika is_booked true, kursi tidak bisa diklik -->
                    <div
                        class="seat-item {{ $seat->is_booked ? 'booked' : 'available' }}"
                        style="padding: 10px; border: 1px solid #ccc; text-align: center; {{ $seat->is_booked ? 'background-color: #f8d7da; cursor: not-allowed;' : 'background-color: #d4edda; cursor: pointer;' }}"
                        data-seat-number="{{ $seat->seat_number }}"
                    >
                        {{ $seat->seat_number }}
                    </div>
                @endforeach
            @else
                <p>Data kursi belum tersedia untuk jadwal ini.</p>
            @endif
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Lanjut ke Data Penumpang</button>
        </div>
    </form>
@endsection
