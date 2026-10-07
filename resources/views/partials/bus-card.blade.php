<div class="card bus-card" style="border: 1px solid #ddd; padding: 16px; margin-bottom: 16px; border-radius: 8px;">
    <div class="bus-info">
        <h3 style="margin-top: 0;">{{ $schedule->origin }} &rarr; {{ $schedule->destination }}</h3>
        <p style="margin: 4px 0;"><strong>Berangkat:</strong> {{ \Carbon\Carbon::parse($schedule->departure_time)->format('d M Y, H:i') }}</p>
        <p style="margin: 4px 0;"><strong>Harga:</strong> Rp{{ number_format($schedule->price, 0, ',', '.') }}</p>
    </div>

    <div class="bus-action" style="margin-top: 12px;">
        <!-- Link ini akan mengarah ke rute pemilihan kursi berdasarkan ID jadwal spesifik -->
        <a href="{{ route('booking.seats', $schedule->id) }}" class="btn" style="background-color: #007bff; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; display: inline-block;">Pilih Kursi</a>
    </div>
</div>
