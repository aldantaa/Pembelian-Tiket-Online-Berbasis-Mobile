<?php
// Booking.blade.php - Halaman Detail Pemesanan My Halte (Blade / PHP Template)

// Parameter Tangkapan / Sample Data Perjalanan & Kursi
$schedule = [
    'id' => isset($_REQUEST['schedule_id']) ? $_REQUEST['schedule_id'] : '1',
    'busName' => isset($_REQUEST['bus_name']) ? $_REQUEST['bus_name'] : 'Sinar Jaya Executive',
    'busType' => isset($_REQUEST['bus_type']) ? $_REQUEST['bus_type'] : 'Executive',
    'departure' => isset($_REQUEST['departure']) ? $_REQUEST['departure'] : 'Jakarta (Pulo Gebang)',
    'arrival' => isset($_REQUEST['arrival']) ? $_REQUEST['arrival'] : 'Surabaya (Purabaya)',
    'departureTime' => isset($_REQUEST['departure_time']) ? $_REQUEST['departure_time'] : '08:00',
    'arrivalTime' => isset($_REQUEST['arrival_time']) ? $_REQUEST['arrival_time'] : '18:00',
    'price' => isset($_REQUEST['price']) ? (int)$_REQUEST['price'] : 250000,
];

// Pilihan Kursi (Default sample: 1A, 1B)
$selectedSeats = isset($_REQUEST['selected_seats']) ? (is_array($_REQUEST['selected_seats']) ? $_REQUEST['selected_seats'] : explode(',', $_REQUEST['selected_seats'])) : ['1A', '1B'];
$totalPrice = $schedule['price'] * count($selectedSeats);

// Validasi Form
$error = '';
$passengerName = isset($_POST['passengerName']) ? trim($_POST['passengerName']) : '';
$passengerEmail = isset($_POST['passengerEmail']) ? trim($_POST['passengerEmail']) : '';
$passengerPhone = isset($_POST['passengerPhone']) ? trim($_POST['passengerPhone']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($passengerName) || empty($passengerEmail) || empty($passengerPhone)) {
        $error = 'Semua field harus diisi';
    } elseif (strpos($passengerEmail, '@') === false) {
        $error = 'Format email tidak valid';
    } elseif (strlen($passengerPhone) < 10) {
        $error = 'Nomor telepon tidak valid (minimal 10 digit)';
    } else {
        // Berhasil validasi -> Lanjut ke Halaman Pembayaran
        $params = http_build_query([
            'bus_name' => $schedule['busName'],
            'passengerName' => $passengerName,
            'passengerEmail' => $passengerEmail,
            'passengerPhone' => $passengerPhone,
            'totalPrice' => $totalPrice,
            'seats' => implode(',', $selectedSeats)
        ]);
        echo "<script>alert('Pemesanan Berhasil! Mengalihkan ke Halaman Pembayaran...'); window.location.href='Payment.blade.php?$params';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Halte - Detail Pemesanan</title>
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
                <a href="Search.blade.php" class="p-2 text-gray-600 hover:bg-slate-100 rounded-lg transition" title="Kembali">
                    <!-- ArrowLeft Icon -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Detail Pemesanan</h1>
                    <p class="text-sm text-gray-500">Lengkapi data penumpang</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6 max-w-4xl">
        <div class="grid md:grid-cols-3 gap-6">
            
            <!-- Left Column: Form & Trip Details -->
            <div class="md:col-span-2 space-y-6">
                
                <!-- Trip Details Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-900 text-lg mb-4">Detail Perjalanan</h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="text-gray-500">Bus:</span>
                            <span class="font-semibold text-gray-900"><?= htmlspecialchars($schedule['busName']) ?></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="text-gray-500">Tipe:</span>
                            <span class="font-semibold text-gray-900"><?= htmlspecialchars($schedule['busType']) ?></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="text-gray-500">Rute:</span>
                            <span class="font-semibold text-gray-900">
                                <?= htmlspecialchars($schedule['departure']) ?> &rarr; <?= htmlspecialchars($schedule['arrival']) ?>
                            </span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="text-gray-500">Waktu:</span>
                            <span class="font-semibold text-gray-900">
                                <?= htmlspecialchars($schedule['departureTime']) ?> - <?= htmlspecialchars($schedule['arrivalTime']) ?>
                            </span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-500">Kursi Dipilih:</span>
                            <span class="font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">
                                <?= htmlspecialchars(implode(', ', $selectedSeats)) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Passenger Input Form Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-900 text-lg mb-4">Data Penumpang</h3>

                    <?php if (!empty($error)): ?>
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-600 p-3 rounded-xl text-sm flex items-center gap-2">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span><?= htmlspecialchars($error) ?></span>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="Booking.blade.php" class="space-y-4">
                        <input type="hidden" name="schedule_id" value="<?= htmlspecialchars($schedule['id']) ?>">
                        <input type="hidden" name="bus_name" value="<?= htmlspecialchars($schedule['busName']) ?>">
                        <input type="hidden" name="bus_type" value="<?= htmlspecialchars($schedule['busType']) ?>">
                        <input type="hidden" name="departure" value="<?= htmlspecialchars($schedule['departure']) ?>">
                        <input type="hidden" name="arrival" value="<?= htmlspecialchars($schedule['arrival']) ?>">
                        <input type="hidden" name="departure_time" value="<?= htmlspecialchars($schedule['departureTime']) ?>">
                        <input type="hidden" name="arrival_time" value="<?= htmlspecialchars($schedule['arrivalTime']) ?>">
                        <input type="hidden" name="price" value="<?= $schedule['price'] ?>">
                        <input type="hidden" name="selected_seats" value="<?= htmlspecialchars(implode(',', $selectedSeats)) ?>">

                        <!-- Name Input -->
                        <div>
                            <label for="passengerName" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                            <div class="relative">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <input
                                    type="text"
                                    id="passengerName"
                                    name="passengerName"
                                    value="<?= htmlspecialchars($passengerName) ?>"
                                    placeholder="Nama lengkap sesuai KTP"
                                    required
                                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                                />
                            </div>
                        </div>

                        <!-- Email Input -->
                        <div>
                            <label for="passengerEmail" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                            <div class="relative">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <input
                                    type="email"
                                    id="passengerEmail"
                                    name="passengerEmail"
                                    value="<?= htmlspecialchars($passengerEmail) ?>"
                                    placeholder="email@contoh.com"
                                    required
                                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                                />
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                E-ticket akan dikirim ke email ini
                            </p>
                        </div>

                        <!-- Phone Input -->
                        <div>
                            <label for="passengerPhone" class="block text-sm font-semibold text-gray-700 mb-1">Nomor Telepon</label>
                            <div class="relative">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <input
                                    type="tel"
                                    id="passengerPhone"
                                    name="passengerPhone"
                                    value="<?= htmlspecialchars($passengerPhone) ?>"
                                    placeholder="08123456789"
                                    required
                                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                                />
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl shadow transition"
                        >
                            Lanjut ke Pembayaran
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Order Summary (Sticky) -->
            <div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <h3 class="font-bold text-gray-900 text-lg mb-4">Ringkasan Pembayaran</h3>

                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Harga tiket</span>
                            <span class="font-medium text-gray-900">Rp <?= number_format($schedule['price'], 0, ',', '.') ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Jumlah penumpang</span>
                            <span class="font-medium text-gray-900"><?= count($selectedSeats) ?> orang</span>
                        </div>

                        <hr class="border-gray-100 my-2" />

                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="font-medium text-gray-900">Rp <?= number_format($totalPrice, 0, ',', '.') ?></span>
                        </div>

                        <hr class="border-gray-100 my-2" />

                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-900">Total Pembayaran</span>
                            <span class="text-2xl font-bold text-blue-600">
                                Rp <?= number_format($totalPrice, 0, ',', '.') ?>
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-100">
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Dengan melanjutkan, Anda menyetujui syarat dan ketentuan yang berlaku di My Halte.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </main>

</body>
</html>
