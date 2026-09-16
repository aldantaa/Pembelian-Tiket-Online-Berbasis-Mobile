<?php
// ETicket.blade.php - Halaman E-Ticket Bus My Halte (Blade / PHP Template)

// Tangkap Data atau Gunakan Sample Data
$passengerName = isset($_REQUEST['passengerName']) && !empty($_REQUEST['passengerName']) ? $_REQUEST['passengerName'] : 'Budi Santoso';
$passengerEmail = isset($_REQUEST['passengerEmail']) && !empty($_REQUEST['passengerEmail']) ? $_REQUEST['passengerEmail'] : 'budi.santoso@example.com';
$passengerPhone = isset($_REQUEST['passengerPhone']) && !empty($_REQUEST['passengerPhone']) ? $_REQUEST['passengerPhone'] : '08123456789';

$busName = isset($_REQUEST['bus_name']) && !empty($_REQUEST['bus_name']) ? $_REQUEST['bus_name'] : 'Sinar Jaya Executive';
$busType = isset($_REQUEST['bus_type']) && !empty($_REQUEST['bus_type']) ? $_REQUEST['bus_type'] : 'Executive';
$departure = isset($_REQUEST['departure']) && !empty($_REQUEST['departure']) ? $_REQUEST['departure'] : 'Jakarta (Pulo Gebang)';
$arrival = isset($_REQUEST['arrival']) && !empty($_REQUEST['arrival']) ? $_REQUEST['arrival'] : 'Surabaya (Purabaya)';
$departureTime = isset($_REQUEST['departure_time']) && !empty($_REQUEST['departure_time']) ? $_REQUEST['departure_time'] : '08:00';
$arrivalTime = isset($_REQUEST['arrival_time']) && !empty($_REQUEST['arrival_time']) ? $_REQUEST['arrival_time'] : '18:00';

$totalPrice = isset($_REQUEST['totalPrice']) && !empty($_REQUEST['totalPrice']) ? (int)$_REQUEST['totalPrice'] : 500000;
$selectedSeats = isset($_REQUEST['seats']) && !empty($_REQUEST['seats']) ? (is_array($_REQUEST['seats']) ? $_REQUEST['seats'] : explode(',', $_REQUEST['seats'])) : ['1A', '1B'];

$bookingCode = isset($_REQUEST['code']) && !empty($_REQUEST['code']) ? $_REQUEST['code'] : 'MHT-882910';
$bookingDate = isset($_REQUEST['date']) && !empty($_REQUEST['date']) ? $_REQUEST['date'] : date('Y-m-d');
$qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($bookingCode);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Halte - E-Ticket Pembayaran Berhasil</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50">

    <main class="container mx-auto px-4 py-8 max-w-2xl">
        
        <!-- Header Success Message -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4 shadow-sm">
                <!-- CheckCircle2 Icon -->
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Pembayaran Berhasil!</h1>
            <p class="text-gray-600">
                E-ticket Anda telah dikirim ke email<br />
                <span class="font-semibold text-gray-900"><?= htmlspecialchars($passengerEmail) ?></span>
            </p>
        </div>

        <!-- E-Ticket Main Card -->
        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 mb-6 overflow-hidden">
            
            <!-- Ticket Banner Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-2xl font-bold"><?= htmlspecialchars($busName) ?></h2>
                        <p class="text-blue-100 text-sm"><?= htmlspecialchars($busType) ?></p>
                    </div>
                    <div class="bg-white text-blue-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider shadow">
                        E-Ticket
                    </div>
                </div>

                <!-- Departure & Arrival Route -->
                <div class="flex items-center justify-between mt-6">
                    <div>
                        <div class="text-xs text-blue-100">Dari</div>
                        <div class="text-xl font-bold"><?= htmlspecialchars($departure) ?></div>
                        <div class="text-sm font-medium"><?= htmlspecialchars($departureTime) ?></div>
                    </div>

                    <div class="flex-1 border-t-2 border-dashed border-blue-300 mx-4 opacity-75"></div>

                    <div class="text-right">
                        <div class="text-xs text-blue-100">Ke</div>
                        <div class="text-xl font-bold"><?= htmlspecialchars($arrival) ?></div>
                        <div class="text-sm font-medium"><?= htmlspecialchars($arrivalTime) ?></div>
                    </div>
                </div>
            </div>

            <!-- Ticket Body Details -->
            <div class="p-6">
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <!-- Passenger Name -->
                    <div>
                        <div class="flex items-center gap-1.5 text-xs text-gray-500 mb-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>Nama Penumpang</span>
                        </div>
                        <div class="font-semibold text-gray-900"><?= htmlspecialchars($passengerName) ?></div>
                    </div>

                    <!-- Departure Date -->
                    <div>
                        <div class="flex items-center gap-1.5 text-xs text-gray-500 mb-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Tanggal Keberangkatan</span>
                        </div>
                        <div class="font-semibold text-gray-900">
                            <?= date('d F Y', strtotime($bookingDate)) ?>
                        </div>
                    </div>

                    <!-- Selected Seats -->
                    <div>
                        <div class="flex items-center gap-1.5 text-xs text-gray-500 mb-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            <span>Nomor Kursi</span>
                        </div>
                        <div class="font-semibold text-gray-900"><?= htmlspecialchars(implode(', ', $selectedSeats)) ?></div>
                    </div>

                    <!-- Booking Code -->
                    <div>
                        <div class="flex items-center gap-1.5 text-xs text-gray-500 mb-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Kode Booking</span>
                        </div>
                        <div class="font-bold text-blue-600 font-mono tracking-wider"><?= htmlspecialchars($bookingCode) ?></div>
                    </div>
                </div>

                <hr class="my-6 border-gray-100" />

                <!-- QR Code Box -->
                <div class="bg-slate-50 rounded-2xl p-6 text-center border border-gray-100">
                    <p class="text-sm font-medium text-gray-600 mb-4">QR Code E-Ticket</p>
                    <div class="inline-block bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                        <img
                            src="<?= htmlspecialchars($qrCodeUrl) ?>"
                            alt="QR Code Tiket"
                            class="w-48 h-48 mx-auto"
                        />
                    </div>
                    <p class="text-xs text-gray-500 mt-4">
                        Tunjukkan QR code ini kepada petugas saat boarding
                    </p>
                </div>

                <hr class="my-6 border-gray-100" />

                <!-- Price Summary -->
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">Total Pembayaran</span>
                    <span class="text-2xl font-bold text-blue-600">
                        Rp <?= number_format($totalPrice, 0, ',', '.') ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Download & Share Action Buttons -->
        <div class="flex gap-3 mb-6">
            <button onclick="window.print()" class="flex-1 bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 font-semibold py-3 px-4 rounded-xl flex items-center justify-center transition shadow-sm">
                <!-- Download Icon -->
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Unduh E-Ticket (PDF)
            </button>
            <button onclick="shareTicket()" class="flex-1 bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 font-semibold py-3 px-4 rounded-xl flex items-center justify-center transition shadow-sm">
                <!-- Share Icon -->
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 100-5.368 3 3 0 000 5.368zm0 8.004a3 3 0 100-5.368 3 3 0 000 5.368z"></path>
                </svg>
                Bagikan
            </button>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex gap-3 mb-6">
            <a href="HomePage.php" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl flex items-center justify-center transition shadow">
                <!-- Home Icon -->
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Kembali ke Beranda
            </a>
            <a href="OrderHistoryPage.php" class="flex-1 bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 font-semibold py-3 px-4 rounded-xl flex items-center justify-center transition shadow-sm">
                <!-- History Icon -->
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Lihat Riwayat
            </a>
        </div>

        <!-- Important Notes Card -->
        <div class="bg-blue-50 rounded-2xl border border-blue-200 p-5">
            <h4 class="font-semibold text-gray-900 mb-2 text-sm">Catatan Penting:</h4>
            <ul class="text-xs text-gray-600 space-y-1.5 list-disc list-inside">
                <li>Harap datang minimal 30 menit sebelum waktu keberangkatan</li>
                <li>Tunjukkan E-ticket atau QR code di atas kepada petugas saat boarding</li>
                <li>Pastikan membawa identitas diri asli (KTP / SIM / Paspor)</li>
                <li>Hubungi customer service My Halte jika Anda memiliki pertanyaan</li>
            </ul>
        </div>

    </main>

    <script>
        function shareTicket() {
            if (navigator.share) {
                navigator.share({
                    title: 'E-Ticket My Halte',
                    text: 'Tiket Bus <?= addslashes($busName) ?> - Kode Booking: <?= addslashes($bookingCode) ?>',
                    url: window.location.href
                }).catch(() => {});
            } else {
                alert('Fitur berbagi tidak didukung di browser ini. Kode Booking Anda: <?= htmlspecialchars($bookingCode) ?>');
            }
        }
    </script>
</body>
</html>
