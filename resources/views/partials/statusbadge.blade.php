@php
    $labels = ['pending' => 'Menunggu pembayaran', 'success' => 'Berhasil', 'failed' => 'Gagal', 'expired' => 'Kedaluwarsa'];
@endphp
<span class="status status--{{ $status }}">{{ $labels[$status] ?? 'Menunggu pembayaran' }}</span>