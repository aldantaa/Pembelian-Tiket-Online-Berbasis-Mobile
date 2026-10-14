@extends('layouts.master')
@section('title', 'Pembayaran')

@section('content')
@include('partials.stepper', ['active' => 4])

<div class="two-col">
    <form method="GET" action="{{ route('ticket') }}" class="card">
        <h2>Metode pembayaran</h2>
        <input type="hidden" name="bus" value="{{ $bus['id'] }}">
        <input type="hidden" name="seats" value="{{ implode(',', $seats) }}">
        <input type="hidden" name="from" value="{{ $query['from'] ?? '' }}">
        <input type="hidden" name="to" value="{{ $query['to'] ?? '' }}">
        <input type="hidden" name="date" value="{{ $query['date'] ?? '' }}">
        <div class="method-list">
            @foreach ($methods as $i => [$name, $type])
                <label class="method">
                    <input type="radio" name="method" value="{{ $name }}" {{ $i === 0 ? 'checked' : '' }}>
                    <span><b>{{ $name }}</b><small>{{ $type }}</small></span>
                </label>
            @endforeach
        </div>
        <ol class="instructions">
            <li>Klik tombol "Bayar sekarang".</li>
            <li>Selesaikan pembayaran sesuai metode yang dipilih.</li>
            <li>E-tiket dikirim ke email Anda setelah pembayaran berhasil.</li>
        </ol>
        <button class="btn btn--block">Bayar sekarang</button>
    </form>
    @include('partials.summary')
</div>
@endsection
