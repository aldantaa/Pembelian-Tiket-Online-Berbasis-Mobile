
@extends('layouts.master')

@section('title', 'Pilih Kursi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/seats.css') }}">
@endpush

@section('content')
    @include('partials.stepper', ['active' => 2])

    <form
        method="GET"
        action="{{ route('passenger') }}"
        class="seat-page"
        data-price="{{ $schedule->harga }}"
    >
        <input
            type="hidden"
            name="bus"
            value="{{ $schedule->id }}"
        >

        <input
            type="hidden"
            name="seats"
            id="seatsInput"
            value=""
        >

        <input
            type="hidden"
            name="from"
            value="{{ request('from', $schedule->kota_asal) }}"
        >

        <input
            type="hidden"
            name="to"
            value="{{ request('to', $schedule->kota_tujuan) }}"
        >

        <input
            type="hidden"
            name="date"
            value="{{ request('date', $schedule->waktu_keberangkatan->format('Y-m-d')) }}"
        >

        <div class="section-title">
            <h2>Pilih Kursi Anda</h2>

            <span class="muted">
                {{ $schedule->kota_asal }}
                &rarr;
                {{ $schedule->kota_tujuan }}
            </span>
        </div>

        <p>
            Harga per kursi:
            Rp{{ number_format($schedule->harga, 0, ',', '.') }}
        </p>

        <p>
            Total kursi: {{ $totalSeats }}
            |
            Kursi tersedia: {{ $availableSeats }}
        </p>

        <div
            class="seat-grid"
            style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin: 20px 0;"
        >
            @forelse ($seats as $seat)
                <div
                    class="seat-item {{ $seat->is_booked ? 'booked' : 'available' }}"
                    style="padding: 10px; border: 1px solid #ccc; text-align: center; {{ $seat->is_booked ? 'background-color: #f8d7da; cursor: not-allowed;' : 'background-color: #d4edda; cursor: pointer;' }}"
                    data-seat-number="{{ $seat->seat_number }}"
                    data-booked="{{ $seat->is_booked ? '1' : '0' }}"
                    aria-pressed="false"
                >
                    {{ $seat->seat_number }}

                    @if ($seat->is_booked)
                        <span>(Terisi)</span>
                    @endif
                </div>
            @empty
                <p>
                    Data kursi belum tersedia untuk jadwal ini.
                </p>
            @endforelse
        </div>

        <p>
            Kursi dipilih:
            <span id="selectedSeatsText">Belum ada</span>
        </p>

        <p>
            Jumlah kursi dipilih:
            <span id="selectedCount">0</span>
        </p>

        <p>
            Total harga:
            Rp<span id="totalPrice">0</span>
        </p>

        <div class="form-actions">
            <button type="submit" class="btn">
                Lanjut ke Data Penumpang
            </button>
        </div>
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

    if (!form || !seatsInput) {
        return;
    }

    const pricePerSeat = Number(form.dataset.price || 0);
    const selectedSeats = new Set();

    function updateSummary() {
        const seats = Array.from(selectedSeats);

        // Kirim nomor kursi sebagai string dipisahkan koma.
        seatsInput.value = seats.join(',');

        selectedSeatsText.textContent =
            seats.length > 0 ? seats.join(', ') : 'Belum ada';

        selectedCount.textContent = String(seats.length);

        totalPrice.textContent = (
            seats.length * pricePerSeat
        ).toLocaleString('id-ID');
    }

    document.querySelectorAll('.seat-item').forEach(function (seatElement) {
        seatElement.addEventListener('click', function () {
            // Kursi yang sudah terisi tidak bisa dipilih.
            if (seatElement.dataset.booked === '1') {
                return;
            }

            const seatNumber = seatElement.dataset.seatNumber;

            if (selectedSeats.has(seatNumber)) {
                selectedSeats.delete(seatNumber);

                seatElement.style.backgroundColor = '#d4edda';
                seatElement.style.border = '1px solid #ccc';
                seatElement.setAttribute('aria-pressed', 'false');
            } else {
                selectedSeats.add(seatNumber);

                seatElement.style.backgroundColor = '#b8daff';
                seatElement.style.border = '2px solid #007bff';
                seatElement.setAttribute('aria-pressed', 'true');
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
