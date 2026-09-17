<?php
// Mock data jadwal bus jika belum dilempar dari controller Laravel
$schedule = $schedule ?? (object) [
    'busName'       => 'MyHalte Trans Eksekutif',
    'departure'     => 'Halte Terminal Purbaya',
    'arrival'       => 'Halte Alun-Alun Kota',
    'departureTime' => '08:00',
    'arrivalTime'   => '09:30',
    'busType'       => 'Eksekutif AC',
    'price'         => 25000,
];

// Daftar kursi yang sudah dipesan (nantinya bisa ditarik dari database)
$bookedSeats = ['A1', 'A2', 'B3', 'C1', 'D2', 'E4', 'F1', 'G3'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Kursi - MyHalte</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen bg-slate-50 text-gray-900 font-sans antialiased">

    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-4xl mx-auto px-4 py-4 flex items-center gap-4">
            <button type="button" onclick="history.back()" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </button>
            <div>
                <h1 class="text-xl font-bold tracking-tight">Pilih Kursi</h1>
                <p class="text-sm text-gray-600"><?= htmlspecialchars($schedule->busName) ?></p>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-6">
        <!-- Informasi Rute Bus -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">
                        <?= htmlspecialchars($schedule->departure) ?> &rarr; <?= htmlspecialchars($schedule->arrival) ?>
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">
                        <?= htmlspecialchars($schedule->departureTime) ?> - <?= htmlspecialchars($schedule->arrivalTime) ?>
                    </p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                    <?= htmlspecialchars($schedule->busType) ?>
                </span>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-6 mb-6">
            <!-- Denah Kursi Bus -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="mb-6">
                        <!-- Posisi Supir -->
                        <div class="bg-gray-800 text-white text-center py-3 rounded-t-xl mb-6">
                            <i data-lucide="armchair" class="w-6 h-6 mx-auto mb-1"></i>
                            <p class="text-xs uppercase tracking-wider font-semibold">Pengemudi</p>
                        </div>

                        <!-- Baris Kursi (10 Baris x 4 Kursi) -->
                        <div class="space-y-3">
                            <?php
                            $rows = 10;
                            for ($r = 0; $r < $rows; $r++):
                                $rowLetter = chr(65 + $r);
                            ?>
                                <div class="flex justify-center gap-3">
                                    <!-- 2 Kursi Sebelah Kiri -->
                                    <div class="flex gap-2">
                                        <?php for ($s = 1; $s <= 2; $s++): 
                                            $seatNo = $rowLetter . $s;
                                            $isBooked = in_array($seatNo, $bookedSeats);
                                        ?>
                                            <button type="button"
                                                data-seat="<?= $seatNo ?>"
                                                <?= $isBooked ? 'disabled' : '' ?>
                                                onclick="toggleSeat('<?= $seatNo ?>')"
                                                class="seat-btn w-12 h-12 rounded-lg flex items-center justify-center text-sm font-semibold transition-all <?= $isBooked ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-white border-2 border-gray-300 hover:border-blue-400 cursor-pointer text-gray-800' ?>">
                                                <?= $seatNo ?>
                                            </button>
                                        <?php endfor; ?>
                                    </div>

                                    <!-- Lorong Jalan Bus -->
                                    <div class="w-8"></div>

                                    <!-- 2 Kursi Sebelah Kanan -->
                                    <div class="flex gap-2">
                                        <?php for ($s = 3; $s <= 4; $s++): 
                                            $seatNo = $rowLetter . $s;
                                            $isBooked = in_array($seatNo, $bookedSeats);
                                        ?>
                                            <button type="button"
                                                data-seat="<?= $seatNo ?>"
                                                <?= $isBooked ? 'disabled' : '' ?>
                                                onclick="toggleSeat('<?= $seatNo ?>')"
                                                class="seat-btn w-12 h-12 rounded-lg flex items-center justify-center text-sm font-semibold transition-all <?= $isBooked ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-white border-2 border-gray-300 hover:border-blue-400 cursor-pointer text-gray-800' ?>">
                                                <?= $seatNo ?>
                                            </button>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <!-- Keterangan Status Kursi -->
                    <div class="flex justify-center gap-6 text-sm pt-4 border-t border-gray-100">
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-5 bg-white border-2 border-gray-300 rounded"></div>
                            <span class="text-gray-600">Tersedia</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-5 bg-blue-600 rounded"></div>
                            <span class="text-gray-600">Dipilih</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-5 bg-gray-300 rounded"></div>
                            <span class="text-gray-600">Terisi</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Ringkasan Biaya & Konfirmasi -->
            <div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <h3 class="font-bold text-gray-900 mb-4">Ringkasan</h3>

                    <div class="space-y-3 mb-4 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Kursi dipilih:</span>
                            <span id="selectedSeatsText" class="font-semibold text-gray-800">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jumlah kursi:</span>
                            <span id="selectedCountText" class="font-semibold text-gray-800">0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Harga per kursi:</span>
                            <span class="font-semibold text-gray-800">Rp <?= number_format($schedule->price, 0, ',', '.') ?></span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 mb-5">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-900">Total:</span>
                            <span id="totalPriceText" class="text-2xl font-bold text-blue-600">Rp 0</span>
                        </div>
                    </div>

                    <!-- Form Pengiriman Kursi Terpilih -->
                    <form action="booking.php" method="POST">
                        <input type="hidden" name="selected_seats" id="inputSelectedSeats" value="">
                        <input type="hidden" name="total_price" id="inputTotalPrice" value="0">

                        <button type="submit" id="btnConfirm" disabled
                            class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                            Lanjutkan
                        </button>
                    </form>

                    <p id="emptyNotice" class="text-xs text-gray-500 text-center mt-2.5">
                        Pilih minimal 1 kursi untuk melanjutkan
                    </p>
                </div>
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();

        const seatPrice = <?= (int)$schedule->price ?>;
        let selectedSeats = [];

        function toggleSeat(seatNo) {
            const btn = document.querySelector(`button[data-seat="${seatNo}"]`);
            if (!btn || btn.disabled) return;

            const index = selectedSeats.indexOf(seatNo);
            if (index > -1) {
                selectedSeats.splice(index, 1);
                btn.className = "seat-btn w-12 h-12 rounded-lg flex items-center justify-center text-sm font-semibold transition-all bg-white border-2 border-gray-300 hover:border-blue-400 cursor-pointer text-gray-800";
            } else {
                selectedSeats.push(seatNo);
                btn.className = "seat-btn w-12 h-12 rounded-lg flex items-center justify-center text-sm font-semibold transition-all bg-blue-600 text-white shadow-sm cursor-pointer";
            }

            updateSummary();
        }

        function updateSummary() {
            const count = selectedSeats.length;
            const total = count * seatPrice;

            document.getElementById('selectedSeatsText').textContent = count > 0 ? selectedSeats.sort().join(', ') : '-';
            document.getElementById('selectedCountText').textContent = count;
            document.getElementById('totalPriceText').textContent = 'Rp ' + total.toLocaleString('id-ID');

            document.getElementById('inputSelectedSeats').value = selectedSeats.join(',');
            document.getElementById('inputTotalPrice').value = total;

            const btnConfirm = document.getElementById('btnConfirm');
            const emptyNotice = document.getElementById('emptyNotice');

            if (count > 0) {
                btnConfirm.removeAttribute('disabled');
                emptyNotice.classList.add('hidden');
            } else {
                btnConfirm.setAttribute('disabled', 'true');
                emptyNotice.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>