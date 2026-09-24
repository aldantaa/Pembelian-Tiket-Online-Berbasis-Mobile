<?php
// Payment.blade.php - Halaman Pembayaran Bus My Halte

// Data Pemesanan dari Form POST / GET
$busName = isset($_REQUEST['bus_name']) ? $_REQUEST['bus_name'] : 'Sinar Jaya Executive';
$busType = isset($_REQUEST['bus_type']) ? $_REQUEST['bus_type'] : 'Executive';
$departure = isset($_REQUEST['departure']) ? $_REQUEST['departure'] : 'Jakarta (Pulo Gebang)';
$arrival = isset($_REQUEST['arrival']) ? $_REQUEST['arrival'] : 'Surabaya (Purabaya)';
$departureTime = isset($_REQUEST['departure_time']) ? $_REQUEST['departure_time'] : '08:00';
$arrivalTime = isset($_REQUEST['arrival_time']) ? $_REQUEST['arrival_time'] : '18:00';
$passengerName = isset($_REQUEST['passengerName']) ? $_REQUEST['passengerName'] : 'Budi Santoso';
$passengerEmail = isset($_REQUEST['passengerEmail']) ? $_REQUEST['passengerEmail'] : 'budi.santoso@example.com';
$passengerPhone = isset($_REQUEST['passengerPhone']) ? $_REQUEST['passengerPhone'] : '08123456789';

$seats = isset($_REQUEST['seats']) ? (is_array($_REQUEST['seats']) ? $_REQUEST['seats'] : explode(',', $_REQUEST['seats'])) : ['1A', '1B'];
$totalPrice = isset($_REQUEST['totalPrice']) ? (int)$_REQUEST['totalPrice'] : 500000;

// Daftar pilihan metode pembayaran
$paymentMethods = [
    ['id' => 'gopay',       'name' => 'GoPay',                  'type' => 'E-Wallet'],
    ['id' => 'ovo',         'name' => 'OVO',                    'type' => 'E-Wallet'],
    ['id' => 'dana',        'name' => 'DANA',                   'type' => 'E-Wallet'],
    ['id' => 'bca',         'name' => 'BCA Virtual Account',    'type' => 'Bank Transfer'],
    ['id' => 'mandiri',     'name' => 'Mandiri Virtual Account','type' => 'Bank Transfer'],
    ['id' => 'bni',         'name' => 'BNI Virtual Account',    'type' => 'Bank Transfer'],
    ['id' => 'credit-card', 'name' => 'Kartu Kredit/Debit',     'type' => 'Card'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Halte - Pembayaran</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-gray-900">

    <!-- Header Navigation -->
    <header class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-4xl mx-auto px-4 py-4 flex items-center gap-4">
            <a href="Booking.blade.php" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition" title="Kembali">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold tracking-tight text-gray-900">Pembayaran</h1>
                <p class="text-sm text-gray-500">Pilih metode pembayaran</p>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-6">
        <div class="grid md:grid-cols-3 gap-6">
            
            <!-- Kolom Kiri: Pilihan Metode Pembayaran & Panduan -->
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-900 text-lg mb-4">Metode Pembayaran</h3>

                    <div class="space-y-3">
                        <?php foreach ($paymentMethods as $method): ?>
                            <div>
                                <button type="button"
                                    data-method-id="<?= $method['id'] ?>"
                                    onclick="selectPaymentMethod('<?= $method['id'] ?>')"
                                    class="payment-option-btn w-full p-4 rounded-xl border-2 border-gray-200 hover:border-gray-300 transition-all text-left flex items-center gap-4">
                                    
                                    <div class="icon-container text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>

                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-800"><?= htmlspecialchars($method['name']) ?></div>
                                        <div class="text-sm text-gray-500"><?= htmlspecialchars($method['type']) ?></div>
                                    </div>

                                    <div class="check-icon hidden text-blue-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Petunjuk Pembayaran Dinamis -->
                <div id="instructionCard" class="hidden bg-blue-50 border border-blue-200 rounded-2xl p-6">
                    <h4 class="font-semibold text-blue-900 mb-2">Instruksi Pembayaran</h4>
                    <ol class="text-sm text-blue-800 space-y-2 list-decimal list-inside">
                        <li>Klik tombol "Bayar Sekarang" di panel samping</li>
                        <li>Selesaikan transfer sesuai tagihan metode yang dipilih</li>
                        <li>Sistem otomatis memverifikasi transaksi tiket Anda</li>
                        <li>E-tiket langsung terbit dan dikirim ke email Anda</li>
                    </ol>
                </div>
            </div>

            <!-- Kolom Kanan: Rincian Tiket & Submit Form -->
            <div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <h3 class="font-bold text-gray-900 mb-4 text-lg">Detail Pesanan</h3>

                    <div class="space-y-3 mb-4">
                        <div class="text-sm">
                            <div class="text-gray-500">Rute</div>
                            <div class="font-semibold text-gray-900">
                                <?= htmlspecialchars($departure) ?> &rarr; <?= htmlspecialchars($arrival) ?>
                            </div>
                        </div>

                        <div class="text-sm">
                            <div class="text-gray-500">Bus</div>
                            <div class="font-semibold text-gray-900"><?= htmlspecialchars($busName) ?></div>
                        </div>

                        <div class="text-sm">
                            <div class="text-gray-500">Waktu</div>
                            <div class="font-semibold text-gray-900">
                                <?= htmlspecialchars($departureTime) ?> - <?= htmlspecialchars($arrivalTime) ?>
                            </div>
                        </div>

                        <div class="text-sm">
                            <div class="text-gray-500">Kursi</div>
                            <div class="font-semibold text-gray-900"><?= htmlspecialchars(implode(', ', $seats)) ?></div>
                        </div>

                        <div class="text-sm">
                            <div class="text-gray-500">Penumpang</div>
                            <div class="font-semibold text-gray-900"><?= htmlspecialchars($passengerName) ?></div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 my-4"></div>

                    <div class="flex justify-between items-center mb-6">
                        <span class="font-bold text-gray-900">Total Tagihan</span>
                        <span class="text-2xl font-bold text-blue-600">
                            Rp <?= number_format($totalPrice, 0, ',', '.') ?>
                        </span>
                    </div>

                    <!-- Formulir Transaksi mengarah ke ETicket.blade.php -->
                    <form id="paymentForm" action="ETicket.blade.php" method="POST">
                        <input type="hidden" name="payment_method" id="selectedPaymentMethod" value="">
                        <input type="hidden" name="bus_name" value="<?= htmlspecialchars($busName) ?>">
                        <input type="hidden" name="bus_type" value="<?= htmlspecialchars($busType) ?>">
                        <input type="hidden" name="departure" value="<?= htmlspecialchars($departure) ?>">
                        <input type="hidden" name="arrival" value="<?= htmlspecialchars($arrival) ?>">
                        <input type="hidden" name="departure_time" value="<?= htmlspecialchars($departureTime) ?>">
                        <input type="hidden" name="arrival_time" value="<?= htmlspecialchars($arrivalTime) ?>">
                        <input type="hidden" name="passengerName" value="<?= htmlspecialchars($passengerName) ?>">
                        <input type="hidden" name="passengerEmail" value="<?= htmlspecialchars($passengerEmail) ?>">
                        <input type="hidden" name="passengerPhone" value="<?= htmlspecialchars($passengerPhone) ?>">
                        <input type="hidden" name="totalPrice" value="<?= $totalPrice ?>">
                        <input type="hidden" name="seats" value="<?= htmlspecialchars(implode(',', $seats)) ?>">

                        <button type="submit" id="btnPay" disabled
                            class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="btnPayText">Bayar Sekarang</span>
                        </button>
                    </form>

                    <p id="methodNotice" class="text-xs text-gray-500 text-center mt-3">
                        Pilih metode pembayaran terlebih dahulu
                    </p>
                </div>
            </div>

        </div>
    </main>

    <script>
        let currentMethod = '';

        function selectPaymentMethod(methodId) {
            currentMethod = methodId;
            document.getElementById('selectedPaymentMethod').value = methodId;

            // Reset seluruh tombol metode pembayaran
            document.querySelectorAll('.payment-option-btn').forEach(btn => {
                btn.className = 'payment-option-btn w-full p-4 rounded-xl border-2 border-gray-200 hover:border-gray-300 transition-all text-left flex items-center gap-4';
                btn.querySelector('.icon-container').className = 'icon-container text-gray-400';
                btn.querySelector('.check-icon').classList.add('hidden');
            });

            // Beri styling aktif pada opsi yang diklik
            const activeBtn = document.querySelector(`button[data-method-id="${methodId}"]`);
            if (activeBtn) {
                activeBtn.className = 'payment-option-btn w-full p-4 rounded-xl border-2 border-blue-600 bg-blue-50 transition-all text-left flex items-center gap-4';
                activeBtn.querySelector('.icon-container').className = 'icon-container text-blue-600';
                activeBtn.querySelector('.check-icon').classList.remove('hidden');
            }

            // Aktifkan tombol bayar dan tampilkan instruksi
            document.getElementById('btnPay').removeAttribute('disabled');
            document.getElementById('methodNotice').classList.add('hidden');
            document.getElementById('instructionCard').classList.remove('hidden');
        }
    </script>
</body>
</html>