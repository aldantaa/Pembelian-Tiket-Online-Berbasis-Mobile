@extends('layouts.master')

@section('title', 'Pilih Kursi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/seats.css') }}">
@endpush

@section('content')
    @include('partials.stepper', ['active' => 2])

    <form method="GET" action="{{ route('passenger') }}" class="seat-page"
          data-price="{{ $schedule->harga }}" data-max="5">

        <input type="hidden" name="bus" value="{{ $schedule->id }}">
        <input type="hidden" name="seats" id="seatsInput" value="">
        <input type="hidden" name="from" value="{{ request('from', $schedule->kota_asal) }}">
        <input type="hidden" name="to" value="{{ request('to', $schedule->kota_tujuan) }}">
        <input type="hidden" name="date" value="{{ request('date', $schedule->waktu_keberangkatan->format('Y-m-d')) }}">

        {{-- KOLOM KIRI: peta kursi --}}
        <div>
            <div class="section-title">
                <h2>Pilih Kursi Anda</h2>
                <span class="muted">{{ $schedule->kota_asal }} &rarr; {{ $schedule->kota_tujuan }}</span>
            </div>

            <div class="seat-info">
                <span>Total kursi: {{ $totalSeats }}</span>
                <span>Tersedia: {{ $availableSeats }}</span>
            </div>

            @if ($seats->isEmpty())
                <p class="empty">Data kursi belum tersedia untuk jadwal ini.</p>
            @else
                <div class="seat-map">
                    <div class="seat-map__driver">Sopir</div>

                    @foreach ($seats->groupBy(fn ($s) => substr($s->seat_number, 0, 1)) as $rowSeats)
                        <div class="seat-row">
                            @foreach ($rowSeats as $seat)
                                <button type="button"
                                        class="seat {{ $seat->is_booked ? 'seat--taken' : '' }}"
                                        data-seat-number="{{ $seat->seat_number }}"
                                        aria-pressed="false"
                                        title="{{ $seat->is_booked ? 'Sudah terisi' : 'Tersedia' }}"
                                        @disabled($seat->is_booked)>
                                    {{ $seat->seat_number }}
                                </button>

                                @if ($loop->iteration === 2)
                                    <span class="seat-gap"></span>
                                @endif
                            @endforeach
                        </div>
                    @endforeach

                    <div class="seat-legend">
                        <span><span class="seat seat--legend"></span> Tersedia</span>
                        <span><span class="seat seat--legend seat--selected"></span> Dipilih</span>
                        <span><span class="seat seat--legend seat--taken"></span> Terisi</span>
                    </div>
                </div>
            @endif
        </div>

        {{-- KOLOM KANAN: ringkasan --}}
        <aside class="card">
            <p>Harga per kursi: <strong>Rp{{ number_format($schedule->harga, 0, ',', '.') }}</strong></p>
            <p>Kursi dipilih: <span id="selectedSeatsText">Belum ada</span></p>
            <p>Jumlah kursi: <span id="selectedCount">0</span></p>
            <p>Total harga: <strong>Rp<span id="totalPrice">0</span></strong></p>

        <button class="btn btn--block" id="seatNext" disabled>Lanjutkan</button>
        </aside>
    </form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.seat-page');
    const seatsInput = document.getElementById('seatsInput');
    const selectedSeatsText = document.getElementById('selectedSeatsText');
    const selectedCount = document.getElementById('selectedCount');
    const totalPrice = document.getElementById('totalPrice');
    const seatNext = document.getElementById('seatNext');
    if (!form || !seatsInput) return;

    const pricePerSeat = Number(form.dataset.price || 0);
    const maxSeats = Number(form.dataset.max || 5);
    const selectedSeats = new Set();

    function updateSummary() {
    const seats = Array.from(selectedSeats);

    seatsInput.value = seats.join(',');
    selectedSeatsText.textContent = seats.length
        ? seats.join(', ')
        : 'Belum ada';

    selectedCount.textContent = String(seats.length);

    totalPrice.textContent = (seats.length * pricePerSeat)
        .toLocaleString('id-ID');

    // Tombol aktif jika minimal satu kursi dipilih
    seatNext.disabled = seats.length === 0;
}
    document.querySelectorAll('.seat[data-seat-number]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const number = btn.dataset.seatNumber;

            if (selectedSeats.has(number)) {
                selectedSeats.delete(number);
                btn.classList.remove('seat--selected');
                btn.setAttribute('aria-pressed', 'false');
            } else {
                if (selectedSeats.size >= maxSeats) {
                    alert('Maksimal ' + maxSeats + ' kursi per pemesanan.');
                    return;
                }
                selectedSeats.add(number);
                btn.classList.add('seat--selected');
                btn.setAttribute('aria-pressed', 'true');
            }
            updateSummary();
        });
    });

    form.addEventListener('submit', function (event) {
        if (selectedSeats.size === 0) {
            event.preventDefault();
            alert('Silakan pilih minimal satu kursi.');
            return;
        }
        updateSummary();
    });

    updateSummary();
});
</script>
@endpush