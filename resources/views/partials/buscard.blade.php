@php
    // Mengonversi $bus ke array jika berupa Model Eloquent
    $busData = is_array($bus) ? $bus : $bus->toArray();

    // Ambil data fasilitas (Mendukung key 'facilities' maupun 'fasilitas')
    $facilities = $busData['facilities'] ?? $busData['fasilitas'] ?? [];

    // Jika fasilitas berbentuk teks dipisah koma (contoh: "AC,WiFi,Toilet"), ubah ke array
    if (is_string($facilities)) {
        $facilities = array_map('trim', explode(',', $facilities));
    }
@endphp

<div class="bus-card">
    <div class="bus-card-header">
        <h3>{{ $busData['nama_bus'] ?? $busData['name'] ?? 'MyHalte Bus' }}</h3>
        <span class="price">Rp {{ number_format($busData['harga'] ?? 0, 0, ',', '.') }}</span>
    </div>

    <div class="bus-card-body">
        <div class="route-info">
            <div>
                <small>Keberangkatan</small>
                <strong>{{ $busData['kota_asal'] ?? $busData['origin'] ?? '-' }}</strong>
                <span>{{ isset($busData['waktu_keberangkatan']) ? date('H:i', strtotime($busData['waktu_keberangkatan'])) : ($busData['depart'] ?? '-') }}</span>
            </div>
            <div class="arrow">➔</div>
            <div>
                <small>Tujuan</small>
                <strong>{{ $busData['kota_tujuan'] ?? $busData['destination'] ?? '-' }}</strong>
                <span>{{ isset($busData['waktu_tiba']) ? date('H:i', strtotime($busData['waktu_tiba'])) : ($busData['arrive'] ?? '-') }}</span>
            </div>
        </div>

        <div class="tags">
            @foreach ($facilities as $f)
                <span class="tag">{{ $f }}</span>
            @endforeach
        </div>
    </div>

    <div class="bus-card-foot">
        <span>
            Kursi tersedia:
            <b class="text-ok">{{ $busData['kursi_tersedia'] ?? $busData['available'] ?? 0 }}</b> / {{ $busData['kapasitas'] ?? $busData['capacity'] ?? 40 }}
        </span>
        <a href="{{ route('booking.seats', ['schedule_id' => $busData['id'] ?? 1]) }}" class="btn-select">Pilih Bus</a>
    </div>
</div>
