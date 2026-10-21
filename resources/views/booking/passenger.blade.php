@extends('layouts.master')
@section('title', 'Data Penumpang')

@section('content')
@include('partials.stepper', ['active' => 3])

<div class="two-col">
    <form method="POST" action="{{ route('bookings.store') }}" class="card">
        @csrf
        <h2>Data penumpang</h2>

        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
        <input type="hidden" name="seats" value="{{ $seats->pluck('seat_number')->implode(',') }}">

        @foreach ($seats as $i => $seat)
            @php $key = 'passengers.' . $seat->seat_number; @endphp

            <h3>Kursi {{ $seat->seat_number }}</h3>

            <div class="field">
                <label for="name_{{ $seat->id }}">Nama lengkap</label>
                <input id="name_{{ $seat->id }}" type="text"
                       name="passengers[{{ $seat->seat_number }}][name]"
                       value="{{ old($key . '.name', $i === 0 ? auth()->user()->name : '') }}"
                       placeholder="Sesuai identitas" required>
            </div>

            <div class="field">
                <label for="idn_{{ $seat->id }}">No. identitas (KTP/SIM/Paspor)</label>
                <input id="idn_{{ $seat->id }}" type="text"
                       name="passengers[{{ $seat->seat_number }}][id_number]"
                       value="{{ old($key . '.id_number') }}">
            </div>

            <div class="field">
                <label for="phone_{{ $seat->id }}">Nomor telepon</label>
                <input id="phone_{{ $seat->id }}" type="tel"
                       name="passengers[{{ $seat->seat_number }}][phone]"
                       value="{{ old($key . '.phone') }}">
            </div>
        @endforeach

        <button class="btn btn--block">Lanjut ke pembayaran</button>
    </form>

    <aside class="card summary">
        <h3>Ringkasan</h3>
        <dl>
            <dt>Rute</dt><dd>{{ $schedule->kota_asal }} → {{ $schedule->kota_tujuan }}</dd>
            <dt>Berangkat</dt><dd>{{ \Carbon\Carbon::parse($schedule->waktu_keberangkatan)->format('d M Y, H:i') }}</dd>
            <dt>Kursi</dt><dd>{{ $seats->pluck('seat_number')->implode(', ') }}</dd>
            <dt>Harga per kursi</dt><dd>Rp {{ number_format($schedule->harga, 0, ',', '.') }}</dd>
            <dt>Jumlah</dt><dd>{{ $seats->count() }}</dd>
        </dl>
        <div class="summary__total">
            <span>Total</span>
            <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
        </div>
    </aside>
</div>
@endsection
