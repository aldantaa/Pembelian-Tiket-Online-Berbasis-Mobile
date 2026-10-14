@extends('layouts.master')
@section('title', 'Data Penumpang')

@section('content')
@include('partials.stepper', ['active' => 3])

<div class="two-col">
    <form method="GET" action="{{ route('payment') }}" class="card">
        <h2>Data penumpang</h2>
        <input type="hidden" name="bus" value="{{ $bus['id'] }}">
        <input type="hidden" name="seats" value="{{ implode(',', $seats) }}">
        <input type="hidden" name="from" value="{{ $query['from'] ?? '' }}">
        <input type="hidden" name="to" value="{{ $query['to'] ?? '' }}">
        <input type="hidden" name="date" value="{{ $query['date'] ?? '' }}">

        @include('partials.input', ['label' => 'Nama lengkap', 'name' => 'nama', 'placeholder' => 'Sesuai identitas'])
        @include('partials.input', ['label' => 'Email', 'name' => 'email', 'type' => 'email', 'placeholder' => 'E-tiket dikirim ke email ini'])
        @include('partials.input', ['label' => 'Nomor telepon', 'name' => 'no_hp', 'type' => 'tel'])
        <button class="btn btn--block">Lanjut ke pembayaran</button>
    </form>
    @include('partials.summary')
</div>
@endsection
