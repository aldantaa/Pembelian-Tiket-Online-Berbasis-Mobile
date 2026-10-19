@extends('layouts.master')

@section('title', 'Data Penumpang')

@section('content')
    @include('partials.stepper', ['active' => 3])

    <div class="two-col">
        <form method="POST"
              action="{{ route('bookings.store') }}"
              class="card">
            @csrf

            <h2>Data penumpang</h2>

            <input type="hidden"
                   name="schedule_id"
                   value="{{ $schedule->id }}">

            <input type="hidden"
                   name="seats"
                   value="{{ $seats->pluck('seat_number')->implode(',') }}">

@foreach ($seats as $seat)
    <div class="passenger-form">
        <h3>Penumpang kursi {{ $seat->seat_number }}</h3>

        <div class="field">
            <label for="name_{{ $seat->seat_number }}">
                Nama lengkap
            </label>
            <input
                id="name_{{ $seat->seat_number }}"
                type="text"
                name="passengers[{{ $seat->seat_number }}][name]"
                value="{{ old('passengers.'.$seat->seat_number.'.name', auth()->user()->name) }}"
                placeholder="Sesuai identitas"
                maxlength="100"
                required
            >
        </div>

        <div class="field">
            <label for="id_{{ $seat->seat_number }}">
                Nomor identitas
            </label>
            <input
                id="id_{{ $seat->seat_number }}"
                type="text"
                name="passengers[{{ $seat->seat_number }}][id_number]"
                value="{{ old('passengers.'.$seat->seat_number.'.id_number') }}"
                placeholder="Nomor KTP"
                maxlength="30"
            >
        </div>

        <div class="field">
            <label for="phone_{{ $seat->seat_number }}">
                Nomor telepon
            </label>
            <input
                id="phone_{{ $seat->seat_number }}"
                type="tel"
                name="passengers[{{ $seat->seat_number }}][phone]"
                value="{{ old('passengers.'.$seat->seat_number.'.phone', auth()->user()->phone ?? '') }}"
                placeholder="08xxxxxxxxxx"
                maxlength="20"
            >
        </div>
    </div>
@endforeach


            <button type="submit" class="btn btn--block">
                Lanjutkan
            </button>
        </form>

        @include('partials.summary')
    </div>
@endsection
