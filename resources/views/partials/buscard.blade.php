@php
    $facilities = $bus->fasilitas ?? [];
    if (is_string($facilities)) {
        $facilities = array_filter(array_map('trim', explode(',', $facilities)));
    }
    $totalSeats = $bus->seats()->count();
    $availableSeats = $bus->seats()->where('is_booked', false)->count();
@endphp

<div class="card bus-card">
    <div class="bus-card__head">
        <div>
            <h3>{{ $bus->nama_bus ?? '' }}</h3>
            <small class="muted">{{ $bus->kota_asal }} &rarr; {{ $bus->kota_tujuan }}</small>
        </div>
        <div class="bus-card__price">
            Rp {{ number_format($bus->harga, 0, ',', '.') }}
            <small class="muted">per orang</small>
        </div>
    </div>

    <div class="bus-card__time">
        <strong>{{ $bus->waktu_keberangkatan->format('H:i') }}</strong>
        <span class="bus-card__line"></span>
        <strong>{{ $bus->waktu_tiba?->format('H:i') ?? '-' }}</strong>
    </div>

    @if (count($facilities))
        <div class="tags">
            @foreach ($facilities as $f)
                <span class="tag">{{ $f }}</span>
            @endforeach
        </div>
    @endif

    <div class="bus-card__foot">
        <span>
            Kursi tersedia:
            <b class="text-ok">{{ $availableSeats }}</b> / {{ $totalSeats }}
        </span>
        <a href="{{ route('booking.seats', ['schedule_id' => $bus->id]) }}" class="btn">Pilih Bus</a>
    </div>
</div>