<aside class="card summary">
    <h3>Ringkasan</h3>

    <dl>
        <dt>Bus</dt>
        <dd>{{ $schedule->nama_bus }}</dd>

        <dt>Rute</dt>
        <dd>{{ $schedule->kota_asal }} → {{ $schedule->kota_tujuan }}</dd>

        <dt>Waktu</dt>
        <dd>
            {{ $schedule->waktu_keberangkatan->format('H:i') }}
            -
            {{ $schedule->waktu_tiba
                ? $schedule->waktu_tiba->format('H:i')
                : '-' }}
        </dd>

        <dt>Kursi</dt>
        <dd>{{ $seats->pluck('seat_number')->implode(', ') }}</dd>

        <dt>Harga per kursi</dt>
        <dd>Rp {{ number_format($schedule->harga, 0, ',', '.') }}</dd>

        <dt>Jumlah</dt>
        <dd>{{ $seats->count() }}</dd>
    </dl>

    <div class="summary__total">
        <span>Total</span>
        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
    </div>
</aside>
