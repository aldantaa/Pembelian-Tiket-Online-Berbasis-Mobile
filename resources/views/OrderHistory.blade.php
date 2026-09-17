<?php
// OrderHistory.blade.php - Halaman Riwayat Pesanan My Halte (Blade / PHP Template)

// Sample Database Riwayat Pesanan
$bookings = [
    [
        'id' => 'b1a2c3d4-5678-4321-9876-123456789abc',
        'status' => 'paid',
        'eTicket' => ['code' => 'MHT-882910'],
        'bookingDate' => '2026-09-15 14:30:00',
        'selectedSeats' => ['1A', '1B'],
        'totalPrice' => 500000,
        'passengerName' => 'Budi Santoso',
        'passengerEmail' => 'budi.santoso@example.com',
        'schedule' => [
            'busName' => 'Sinar Jaya Executive',
            'busType' => 'Executive',
            'departure' => 'Jakarta (Pulo Gebang)',
            'arrival' => 'Surabaya (Purabaya)',
            'departureTime' => '08:00',
            'arrivalTime' => '18:00'
        ]
    ],
    [
        'id' => 'e5f6g7h8-9012-3456-7890-abcdef123456',
        'status' => 'confirmed',
        'eTicket' => ['code' => 'MHT-882911'],
        'bookingDate' => '2026-09-10 10:15:00',
        'selectedSeats' => ['3C'],
        'totalPrice' => 320000,
        'passengerName' => 'Budi Santoso',
        'passengerEmail' => 'budi.santoso@example.com',
        'schedule' => [
            'busName' => 'Rosalia Indah Super Exec',
            'busType' => 'Super Executive',
            'departure' => 'Bandung (Cicaheum)',
            'arrival' => 'Jogja (Giwangan)',
            'departureTime' => '21:00',
            'arrivalTime' => '04:00'
        ]
    ],
    [
        'id' => 'i9j0k1l2-3456-7890-abcd-ef1234567890',
        'status' => 'pending',
        'eTicket' => null,
        'bookingDate' => '2026-09-16 18:00:00',
        'selectedSeats' => ['4A', '4B'],
        'totalPrice' => 400000,
        'passengerName' => 'Budi Santoso',
        'passengerEmail' => 'budi.santoso@example.com',
        'schedule' => [
            'busName' => 'Pahala Kencana VIP',
            'busType' => 'VIP',
            'departure' => 'Jakarta (Kalideres)',
            'arrival' => 'Semarang (Terboyo)',
            'departureTime' => '19:00',
            'arrivalTime' => '03:00'
        ]
    ],
    [
        'id' => 'm3n4o5p6-7890-abcd-ef12-34567890abcd',
        'status' => 'cancelled',
        'eTicket' => null,
        'bookingDate' => '2026-09-01 11:20:00',
        'selectedSeats' => ['2B'],
        'totalPrice' => 120000,
        'passengerName' => 'Budi Santoso',
        'passengerEmail' => 'budi.santoso@example.com',
        'schedule' => [
            'busName' => 'Eka Cepat',
            'busType' => 'Executive',
            'departure' => 'Solo (Tirtonadi)',
            'arrival' => 'Surabaya (Purabaya)',
            'departureTime' => '10:00',
            'arrivalTime' => '14:00'
        ]
    ]
];

// Helper Badge Status
function getStatusBadge($status) {
    switch ($status) {
        case 'paid':
            return '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-500 text-white">Lunas</span>';
        case 'confirmed':
            return '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-500 text-white">Dikonfirmasi</span>';
        case 'pending':
            return '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-800">Pending</span>';
        case 'cancelled':
            return '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-500 text-white">Dibatalkan</span>';
        default:
            return '';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Halte - Riwayat Pesanan</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50">

    <!-- Header Navigation -->
    <header class="bg-white shadow-sm sticky top-0 z-10">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center gap-4">
                <a href="HomePage.php" class="p-2 text-gray-600 hover:bg-slate-100 rounded-lg transition" title="Kembali">
                    <!-- ArrowLeft Icon -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-xl font-bold text-gray-900">Riwayat Pesanan</h1>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6 max-w-4xl">
        
        <?php if (empty($bookings)): ?>
            <!-- Empty State -->
            <div class="text-center py-16 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Pesanan</h3>
                <p class="text-gray-500 mb-6">
                    Anda belum memiliki riwayat pemesanan tiket
                </p>
                <a href="Search.blade.php" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-xl transition shadow">
                    Pesan Tiket Sekarang
                </a>
            </div>
        <?php else: ?>
            <!-- Order List -->
            <div class="space-y-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900">
                        <?= count($bookings) ?> Pesanan Tiket
                    </h2>
                </div>

                <?php foreach ($bookings as $booking): ?>
                    <?php
                        $code = isset($booking['eTicket']['code']) ? $booking['eTicket']['code'] : substr($booking['id'], 0, 8);
                        $targetUrl = ($booking['status'] === 'paid' || $booking['status'] === 'confirmed')
                            ? "ETicket.blade.php?" . http_build_query([
                                'code' => $code,
                                'bus_name' => $booking['schedule']['busName'],
                                'bus_type' => $booking['schedule']['busType'],
                                'departure' => $booking['schedule']['departure'],
                                'arrival' => $booking['schedule']['arrival'],
                                'departure_time' => $booking['schedule']['departureTime'],
                                'arrival_time' => $booking['schedule']['arrivalTime'],
                                'passengerName' => $booking['passengerName'],
                                'passengerEmail' => $booking['passengerEmail'],
                                'seats' => implode(',', $booking['selectedSeats']),
                                'totalPrice' => $booking['totalPrice']
                            ])
                            : "#";
                    ?>
                    
                    <div
                        onclick="if('<?= $targetUrl ?>' !== '#') window.location.href='<?= $targetUrl ?>'"
                        class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition p-6 cursor-pointer group"
                    >
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <h3 class="font-bold text-lg text-gray-900 group-hover:text-blue-600 transition">
                                        <?= htmlspecialchars($booking['schedule']['busName']) ?>
                                    </h3>
                                    <?= getStatusBadge($booking['status']) ?>
                                </div>
                                <p class="text-sm text-gray-500 font-mono">
                                    Kode Booking: <span class="font-semibold text-gray-700"><?= htmlspecialchars($code) ?></span>
                                </p>
                            </div>
                            <!-- ChevronRight Icon -->
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>

                        <!-- Route & Time Grid -->
                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                <div class="text-sm">
                                    <div class="font-semibold text-gray-900">
                                        <?= htmlspecialchars($booking['schedule']['departure']) ?> &rarr; <?= htmlspecialchars($booking['schedule']['arrival']) ?>
                                    </div>
                                    <div class="text-gray-500">
                                        <?= htmlspecialchars($booking['schedule']['departureTime']) ?> - <?= htmlspecialchars($booking['schedule']['arrivalTime']) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div class="text-sm">
                                    <div class="font-semibold text-gray-900">
                                        <?= date('d F Y', strtotime($booking['bookingDate'])) ?>
                                    </div>
                                    <div class="text-gray-500">
                                        Kursi: <?= htmlspecialchars(implode(', ', $booking['selectedSeats'])) ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Price & Action -->
                        <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                            <div>
                                <div class="text-xs text-gray-500">Total Pembayaran</div>
                                <div class="text-xl font-bold text-blue-600">
                                    Rp <?= number_format($booking['totalPrice'], 0, ',', '.') ?>
                                </div>
                            </div>

                            <?php if ($booking['status'] === 'paid' || $booking['status'] === 'confirmed'): ?>
                                <a
                                    href="<?= $targetUrl ?>"
                                    class="inline-flex items-center text-xs font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-2 rounded-lg transition"
                                >
                                    Lihat E-Ticket &rarr;
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>

</body>
</html>
