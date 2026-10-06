@extends('layouts.master')
@section('title', 'Pilih Kursi')
@push('styles')<link rel="stylesheet" href="{{ asset('css/pages/seats.css') }}">@endpush

@section('content')
@include('partials.stepper', ['active' => 2])

<form method="GET" action="{{ route('passenger') }}" class="seat-page" data-price="{{ $bus['price'] }}">
    <input type="hidden" name="bus" value="{{ $bus['id'] }}">
    <input type="hidden" name="seats" id="seatsInput">

    <div class="card seat-map">
        <div class="seat-map__driver">Pengemudi</div>
        @foreach (range('A', 'J') as $row)
            <div class="seat-row">
                @foreach ([1, 2, 'gap', 3, 4] as $col)
                    @if ($col === 'gap')
                        <span class="seat-gap"></span>
                    @else
                        @php $code = $row.$col; @endphp
                        <button type="button" class="seat {{ in_array($code, $taken) ? 'seat--taken' : '' }}"
                                data-seat="{{ $code }}" {{ in_array($code, $taken) ? 'disabled' : '' }}>{{ $code }}</button>
                    @endif
                @endforeach
            </div>
        @endforeach
        @include('partials.seat-legend')
    </div>

    <aside class="card summary">
        <h3>Ringkasan</h3>
        <dl>
            <dt>Bus</dt><dd>{{ $bus['name'] }}</dd>
            <dt>Kursi dipilih</dt><dd id="seatList">-</dd>
            <dt>Jumlah kursi</dt><dd id="seatCount">0</dd>
            <dt>Harga per kursi</dt><dd>Rp {{ number_format($bus['price'], 0, ',', '.') }}</dd>
        </dl>
        <div class="summary__total"><span>Total</span><strong id="seatTotal">Rp 0</strong></div>
        <button class="btn btn--block" id="seatNext" disabled>Lanjutkan</button>
    </aside>
</form>
@endsection

@push('scripts')<script src="{{ asset('js/seats.js') }}"></script>@endpush
