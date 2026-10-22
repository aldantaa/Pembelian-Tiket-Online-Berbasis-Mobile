
@extends('layouts.master')

@section('title', 'E-Tiket')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/ticket.css') }}">
@endpush

@section('content')
    @include('partials.stepper', ['active' => 5])

    <section class="ticket">
        {{-- Header tiket --}}
        <header class="ticket__head">
            <div>
                <h2>{{ $bus['name'] }}</h2>
                <span>{{ $bus['class'] }}</span>
            </div>

            {{-- Rute perjalanan dari halaman pencarian --}}
            <div class="ticket__route">
                <div>
                    <small>Dari</small>
                    <strong>{{ $query['from'] ?? '—' }}</strong>
                    {{ $bus['depart'] }}
                </div>

                <div class="ticket__arrow" aria-hidden="true">→</div>

                <div>
                    <small>Ke</small>
                    <strong>{{ $query['to'] ?? '—' }}</strong>
                    {{ $bus['arrive'] }}
                </div>
            </div>
        </header>

        {{-- Detail tiket --}}
        <div class="ticket__body">
            <dl>
                <dt>Nama penumpang</dt>
                <dd>{{ auth()->user()->name ?? 'Tiara Maya Lestari' }}</dd>

                <dt>Tanggal keberangkatan</dt>
                <dd>
                    @if (!empty($query['date']))
                        {{ \Carbon\Carbon::parse($query['date'])->translatedFormat('l, d F Y') }}
                    @else
                        -
                    @endif
                </dd>

                <dt>Nomor kursi</dt>
                <dd>{{ implode(', ', $seats) }}</dd>

                <dt>Kode booking</dt>
                <dd class="text-primary">{{ $code }}</dd>

                <dt>Status pembayaran</dt>
                <dd>
                    @include('partials.statusbadge', [
                        'status' => $status
                    ])
                </dd>

                <dt>Total pembayaran</dt>
                <dd>
                    Rp {{ number_format($total, 0, ',', '.') }}
                </dd>
            </dl>

            {{-- QR code hanya ditampilkan jika pembayaran berhasil --}}
            @if ($status === 'success')
                @include('partials.qrcode')
            @else
                <p class="muted">
                    QR code tersedia setelah pembayaran berhasil.
                </p>
            @endif
        </div>

        {{-- Tombol aksi --}}
        <footer class="ticket__actions">
            @if ($status === 'success')
                <button
                    type="button"
                    class="btn"
                    onclick="window.print()"
                >
                    Unduh E-Tiket
                </button>
            @else
                <a class="btn"
   href="{{ route('orders.show', $code) }}">
    Lihat Status Transaksi
</a>
            @endif

            <a class="btn btn--ghost" href="{{ route('tickets.index') }}">
                Lihat Riwayat
            </a>

            <a class="btn btn--ghost" href="{{ route('schedules') }}">
                Kembali ke Beranda
            </a>
        </footer>

        {{-- Catatan perjalanan --}}
        <ul class="ticket__notes">
            <li>Datang 30 menit sebelum waktu keberangkatan.</li>
            <li>Tunjukkan E-Tiket atau QR code saat boarding.</li>
            <li>Bawa identitas diri (KTP/SIM/Paspor).</li>
        </ul>
    </section>
@endsection
