@extends('layouts.master')

@section('title', 'Pembayaran')

@section('content')
    @include('partials.stepper', ['active' => 4])

    <div class="two-col">
        <form method="POST"
              action="{{ route('payment.pay', $booking_code) }}"
              class="card">
            @csrf

            <h2>Metode pembayaran</h2>

            <div class="method-list">
                @foreach ($methods as $i => [$name, $type])
                    <label class="method">
                        <input
                            type="radio"
                            name="method"
                            value="{{ $name }}"
                            {{ $i === 0 ? 'checked' : '' }}
                        >
                        <span>
                            <b>{{ $type }}</b>
                            <small>{{ $name }}</small>
                        </span>
                    </label>
                @endforeach
            </div>

            <p>
                Total pembayaran:
                <strong>Rp{{ number_format($total, 0, ',', '.') }}</strong>
            </p>

            <ol class="instructions">
                <li>Pilih metode pembayaran.</li>
                <li>Klik tombol Bayar sekarang.</li>
                <li>Lanjutkan ke halaman konfirmasi pembayaran.</li>
            </ol>

            <button type="submit" class="btn btn--block">
                Bayar sekarang
            </button>
        </form>

            @if (app()->environment('local'))
        <form method="POST"
            action="{{ route('payment.simulate', $booking_code) }}"
            class="mt-3">
            @csrf

            <button type="submit" class="btn btn--block">
                Simulasikan Pembayaran Berhasil (Lokal)
            </button>
        </form>
    @endif
    </div>
@endsection
