<aside class="card summary">
    <h3>Ringkasan</h3>
    <dl>
        <dt>Bus</dt><dd>{{ $bus['name'] }} ({{ $bus['class'] }})</dd>
        <dt>Waktu</dt><dd>{{ $bus['depart'] }} - {{ $bus['arrive'] }}</dd>
        <dt>Kursi</dt><dd>{{ implode(', ', $seats) }}</dd>
        <dt>Harga per kursi</dt><dd>Rp {{ number_format($bus['price'], 0, ',', '.') }}</dd>
        <dt>Jumlah</dt><dd>{{ count($seats) }}</dd>
    </dl>
    <div class="summary__total">
        <span>Total</span>
        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
    </div>
</aside>
