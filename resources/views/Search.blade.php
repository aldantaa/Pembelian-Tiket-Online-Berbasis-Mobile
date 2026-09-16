<?php
// Search.blade.php - Halaman Pencarian Bus My Halte (Blade / PHP Template)

// Data Kota Autocomplete
$citySuggestions = [
    'Jakarta', 'Bandung', 'Surabaya', 'Jogja', 'Semarang',
    'Solo', 'Malang', 'Medan', 'Makassar', 'Palembang'
];

// Data Rekomendasi Perjalanan
$tripSuggestions = [
    ['from' => 'Jakarta', 'to' => 'Surabaya', 'duration' => '10 jam', 'price' => 'Rp 250.000'],
    ['from' => 'Bandung', 'to' => 'Jogja', 'duration' => '6 jam', 'price' => 'Rp 180.000'],
    ['from' => 'Jakarta', 'to' => 'Bandung', 'duration' => '3 jam', 'price' => 'Rp 100.000'],
    ['from' => 'Jakarta', 'to' => 'Jogja', 'duration' => '8 jam', 'price' => 'Rp 200.000'],
    ['from' => 'Jogja', 'to' => 'Surabaya', 'duration' => '4 jam', 'price' => 'Rp 120.000'],
    ['from' => 'Bandung', 'to' => 'Surabaya', 'duration' => '13 jam', 'price' => 'Rp 300.000'],
];

// Database Jadwal Bus
$allSchedules = [
    // Jakarta -> Surabaya
    [
        'id' => '1', 'departure' => 'Jakarta', 'arrival' => 'Surabaya',
        'departureTime' => '08:00', 'arrivalTime' => '18:00',
        'busName' => 'Sinar Jaya', 'busType' => 'Executive',
        'price' => 250000, 'availableSeats' => 15, 'totalSeats' => 40,
        'facilities' => ['WiFi', 'AC', 'USB Charger', 'Snack']
    ],
    [
        'id' => '2', 'departure' => 'Jakarta', 'arrival' => 'Surabaya',
        'departureTime' => '14:00', 'arrivalTime' => '00:00',
        'busName' => 'Harapan Jaya', 'busType' => 'Super Executive',
        'price' => 350000, 'availableSeats' => 8, 'totalSeats' => 30,
        'facilities' => ['WiFi', 'AC', 'USB Charger', 'Snack', 'Bantal', 'Selimut']
    ],
    [
        'id' => '3', 'departure' => 'Jakarta', 'arrival' => 'Surabaya',
        'departureTime' => '20:00', 'arrivalTime' => '06:00',
        'busName' => 'Malam Express', 'busType' => 'Sleeper',
        'price' => 400000, 'availableSeats' => 5, 'totalSeats' => 24,
        'facilities' => ['WiFi', 'AC', 'USB Charger', 'Snack', 'Bed', 'Selimut']
    ],
    // Bandung -> Jogja
    [
        'id' => '4', 'departure' => 'Bandung', 'arrival' => 'Jogja',
        'departureTime' => '07:00', 'arrivalTime' => '13:00',
        'busName' => 'Budiman', 'busType' => 'Executive',
        'price' => 180000, 'availableSeats' => 20, 'totalSeats' => 40,
        'facilities' => ['WiFi', 'AC', 'USB Charger']
    ],
    [
        'id' => '5', 'departure' => 'Bandung', 'arrival' => 'Jogja',
        'departureTime' => '13:00', 'arrivalTime' => '19:30',
        'busName' => 'Pahala Kencana', 'busType' => 'Super Executive',
        'price' => 230000, 'availableSeats' => 12, 'totalSeats' => 30,
        'facilities' => ['WiFi', 'AC', 'USB Charger', 'Snack', 'Bantal']
    ],
    [
        'id' => '6', 'departure' => 'Bandung', 'arrival' => 'Jogja',
        'departureTime' => '21:00', 'arrivalTime' => '04:00',
        'busName' => 'Rosalia Indah', 'busType' => 'Sleeper',
        'price' => 320000, 'availableSeats' => 6, 'totalSeats' => 24,
        'facilities' => ['WiFi', 'AC', 'USB Charger', 'Snack', 'Bed', 'Selimut']
    ],
    // Jakarta -> Bandung
    [
        'id' => '7', 'departure' => 'Jakarta', 'arrival' => 'Bandung',
        'departureTime' => '06:00', 'arrivalTime' => '09:00',
        'busName' => 'Damri', 'busType' => 'Executive',
        'price' => 100000, 'availableSeats' => 25, 'totalSeats' => 40,
        'facilities' => ['AC', 'USB Charger']
    ],
    [
        'id' => '8', 'departure' => 'Jakarta', 'arrival' => 'Bandung',
        'departureTime' => '10:00', 'arrivalTime' => '13:30',
        'busName' => 'Primajasa', 'busType' => 'Super Executive',
        'price' => 150000, 'availableSeats' => 18, 'totalSeats' => 35,
        'facilities' => ['WiFi', 'AC', 'USB Charger', 'Snack']
    ],
    // Jakarta -> Jogja
    [
        'id' => '9', 'departure' => 'Jakarta', 'arrival' => 'Jogja',
        'departureTime' => '16:00', 'arrivalTime' => '23:00',
        'busName' => 'Sumber Alam', 'busType' => 'Executive',
        'price' => 200000, 'availableSeats' => 22, 'totalSeats' => 40,
        'facilities' => ['WiFi', 'AC', 'USB Charger']
    ],
    [
        'id' => '10', 'departure' => 'Jakarta', 'arrival' => 'Jogja',
        'departureTime' => '20:00', 'arrivalTime' => '04:00',
        'busName' => 'Lorena', 'busType' => 'Super Executive',
        'price' => 280000, 'availableSeats' => 10, 'totalSeats' => 30,
        'facilities' => ['WiFi', 'AC', 'USB Charger', 'Snack', 'Bantal', 'Selimut']
    ],
    // Bandung -> Surabaya
    [
        'id' => '11', 'departure' => 'Bandung', 'arrival' => 'Surabaya',
        'departureTime' => '15:00', 'arrivalTime' => '04:00',
        'busName' => 'Kramat Djati', 'busType' => 'Super Executive',
        'price' => 300000, 'availableSeats' => 9, 'totalSeats' => 30,
        'facilities' => ['WiFi', 'AC', 'USB Charger', 'Snack', 'Bantal', 'Selimut']
    ],
    // Jogja -> Surabaya
    [
        'id' => '12', 'departure' => 'Jogja', 'arrival' => 'Surabaya',
        'departureTime' => '09:00', 'arrivalTime' => '13:00',
        'busName' => 'Ramayana', 'busType' => 'Executive',
        'price' => 120000, 'availableSeats' => 30, 'totalSeats' => 40,
        'facilities' => ['AC', 'USB Charger']
    ]
];

// Menangkap query dari Form GET
$departureQuery = isset($_GET['departure']) ? trim($_GET['departure']) : '';
$arrivalQuery = isset($_GET['arrival']) ? trim($_GET['arrival']) : '';
$dateQuery = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$hasSearched = isset($_GET['search_submit']) || ($departureQuery !== '' && $arrivalQuery !== '');

$filteredSchedules = [];
if ($hasSearched) {
    foreach ($allSchedules as $schedule) {
        if (
            strcasecmp($schedule['departure'], $departureQuery) === 0 &&
            strcasecmp($schedule['arrival'], $arrivalQuery) === 0
        ) {
            $filteredSchedules[] = $schedule;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Halte - Cari Jadwal Bus</title>
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
                <h1 class="text-xl font-bold text-gray-900">Cari Jadwal Bus</h1>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6 max-w-4xl">
        
        <!-- Search Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <form method="GET" action="Search.blade.php" class="space-y-4" id="searchForm">
                <input type="hidden" name="search_submit" value="1">

                <!-- Departure Input -->
                <div class="relative">
                    <label for="departure" class="block text-sm font-semibold text-gray-700 mb-1">Keberangkatan</label>
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3 z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <input
                            type="text"
                            id="departure"
                            name="departure"
                            value="<?= htmlspecialchars($departureQuery) ?>"
                            placeholder="Cari kota asal (misal: Jakarta)"
                            autocomplete="off"
                            required
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        />
                    </div>
                </div>

                <!-- Arrival Input -->
                <div class="relative">
                    <label for="arrival" class="block text-sm font-semibold text-gray-700 mb-1">Tujuan</label>
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3 z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <input
                            type="text"
                            id="arrival"
                            name="arrival"
                            value="<?= htmlspecialchars($arrivalQuery) ?>"
                            placeholder="Cari kota tujuan (misal: Surabaya)"
                            autocomplete="off"
                            required
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        />
                    </div>
                </div>

                <!-- Date Input -->
                <div>
                    <label for="date" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Keberangkatan</label>
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <input
                            type="date"
                            id="date"
                            name="date"
                            value="<?= htmlspecialchars($dateQuery) ?>"
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        />
                    </div>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl flex items-center justify-center transition shadow"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Cari Bus
                </button>
            </form>
        </div>

        <!-- Empty State (Jika tidak ditemukan bus) -->
        <?php if ($hasSearched && empty($filteredSchedules)): ?>
            <div class="text-center py-12 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="text-gray-800 font-semibold text-lg mb-1">
                    Tidak ada bus tersedia
                </p>
                <p class="text-gray-500 text-sm">
                    Tidak ditemukan jadwal bus untuk rute <span class="font-medium text-gray-800"><?= htmlspecialchars($departureQuery) ?> &rarr; <?= htmlspecialchars($arrivalQuery) ?></span>.
                    Coba rute lain atau tanggal yang berbeda.
                </p>
            </div>
        <?php endif; ?>

        <!-- Results List -->
        <?php if ($hasSearched && !empty($filteredSchedules)): ?>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900">
                        <?= count($filteredSchedules) ?> Bus Tersedia
                    </h2>
                    <p class="text-sm text-gray-600">
                        <?= htmlspecialchars($departureQuery) ?> &rarr; <?= htmlspecialchars($arrivalQuery) ?>
                    </p>
                </div>

                <?php foreach ($filteredSchedules as $schedule): ?>
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-bold text-lg text-gray-900"><?= htmlspecialchars($schedule['busName']) ?></h3>
                                <span class="inline-block mt-1 px-3 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full">
                                    <?= htmlspecialchars($schedule['busType']) ?>
                                </span>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-blue-600">
                                    Rp <?= number_format($schedule['price'], 0, ',', '.') ?>
                                </div>
                                <div class="text-sm text-gray-500">per orang</div>
                            </div>
                        </div>

                        <!-- Time Schedule Bar -->
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <div class="font-semibold text-gray-900"><?= htmlspecialchars($schedule['departureTime']) ?></div>
                                    <div class="text-sm text-gray-600"><?= htmlspecialchars($schedule['departure']) ?></div>
                                </div>
                            </div>

                            <div class="flex-1 border-t-2 border-dashed border-gray-300"></div>

                            <div class="flex items-center gap-2">
                                <div class="text-right">
                                    <div class="font-semibold text-gray-900"><?= htmlspecialchars($schedule['arrivalTime']) ?></div>
                                    <div class="text-sm text-gray-600"><?= htmlspecialchars($schedule['arrival']) ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Facilities List -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            <?php foreach ($schedule['facilities'] as $facility): ?>
                                <span class="flex items-center gap-1.5 bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-medium">
                                    <?php if (strtolower($facility) === 'wifi'): ?>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg>
                                    <?php elseif (strtolower($facility) === 'snack'): ?>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 0A9 9 0 013 12a9 9 0 0115.364-6.364z"></path></svg>
                                    <?php else: ?>
                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($facility) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>

                        <!-- Card Footer -->
                        <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                            <div class="text-sm">
                                <span class="text-gray-600">Kursi tersedia: </span>
                                <span class="font-semibold <?= $schedule['availableSeats'] < 10 ? 'text-red-600' : 'text-green-600' ?>">
                                    <?= $schedule['availableSeats'] ?> / <?= $schedule['totalSeats'] ?>
                                </span>
                            </div>
                            <button onclick="alert('Bus dipilih! Mengalihkan ke pemilihan kursi...')" class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-4 py-2 rounded-lg transition shadow">
                                Pilih Bus
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Trip Suggestions Section (Tampil ketika belum/tidak sedang search) -->
        <?php if (!$hasSearched): ?>
            <div>
                <h3 class="text-lg font-semibold mb-3 text-gray-800">Saran Perjalanan Populer</h3>
                <div class="grid md:grid-cols-2 gap-3">
                    <?php foreach ($tripSuggestions as $trip): ?>
                        <div
                            onclick="selectTrip('<?= addslashes($trip['from']) ?>', '<?= addslashes($trip['to']) ?>')"
                            class="bg-white rounded-xl border border-gray-100 p-4 flex items-center justify-between cursor-pointer hover:shadow-md hover:border-blue-300 transition group"
                        >
                            <div class="flex items-center gap-3">
                                <div class="bg-blue-50 p-2 rounded-full group-hover:bg-blue-600 transition">
                                    <svg class="w-4 h-4 text-blue-500 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 font-semibold text-sm text-gray-900">
                                        <span><?= htmlspecialchars($trip['from']) ?></span>
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        <span><?= htmlspecialchars($trip['to']) ?></span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        <?= htmlspecialchars($trip['duration']) ?> &middot; Mulai <?= htmlspecialchars($trip['price']) ?>
                                    </div>
                                </div>
                            </div>
                            <span class="text-xs text-blue-600 font-semibold group-hover:underline">Pilih</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </main>

    <!-- Helper JavaScript untuk autofill dari Saran Perjalanan -->
    <script>
        function selectTrip(from, to) {
            document.getElementById('departure').value = from;
            document.getElementById('arrival').value = to;
            document.getElementById('searchForm').submit();
        }
    </script>
</body>
</html>
