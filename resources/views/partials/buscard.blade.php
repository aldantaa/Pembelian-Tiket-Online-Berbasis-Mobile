
<article class="card bus-card">
    <div class="bus-card__head">
        <div>
            <h3>{{ $bus['name'] }}</h3>
            <span class="badge">{{ $bus['class'] }}</span>
        </div>

        <div class="bus-card__price">
            Rp {{ number_format($bus['price'], 0, ',', '.') }}
            <small>per orang</small>
        </div>
    </div>

    <div class="bus-card__time">
        <strong>{{ $bus['depart'] }}</strong>
        <span class="bus-card__line"></span>
        <strong>{{ $bus['arrive'] }}</strong>
    </div>

    <div class="tags">
        @foreach ($bus['facilities'] as $f)
            <span class="tag">{{ $f }}</span>
        @endforeach
    </div>

    <div class="bus-card__foot">
        <span>
            Kursi tersedia:
            <b class="text-ok">
                {{ $bus['available'] }} / {{ $bus['capacity'] }}
            </b>
        </span>

        <a
            href="{{ route('seats', [
                'id' => $bus['id'],
                'from' => request('from'),
                'to' => request('to'),
                'date' => request('date'),
            ]) }}"
            class="btn btn--sm"
        >
            Pilih bus
        </a>
    </div>
</article>
