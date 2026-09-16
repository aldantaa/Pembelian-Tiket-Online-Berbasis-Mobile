<?php
// AdminDashboard.blade.php - Admin Dashboard My Halte (Blade / PHP Template)

// Mock data statistik admin
$stats = $stats ?? (object) [
    'total_pendapatan' => 14850000,
    'total_tiket'      => 342,
    'total_bus'        => 12,
    'pengguna_aktif'   => 128,
];

// Mock data transaksi tiket terbaru
$recentOrders = $recentOrders ?? [
    [
        'id'        => 'MHT-8921',
        'nama'      => 'Ahmad Fauzi',
        'rute'      => 'Purbaya → Alun-Alun',
        'bus'       => 'MyHalte Trans 01',
        'kursi'     => 'A1, A2',
        'total'     => 50000,
        'status'    => 'Lunas',
        'waktu'     => '10 menit lalu',
    ],
    [
        'id'        => 'MHT-8922',
        'nama'      => 'Siti Nurhaliza',
        'rute'      => 'Terminal Joyoboyo → Bungurasih',
        'bus'       => 'MyHalte Eksekutif',
        'kursi'     => 'B4',
        'total'     => 25000,
        'status'    => 'Menunggu',
        'waktu'     => '25 menit lalu',
    ],
    [
        'id'        => 'MHT-8923',
        'nama'      => 'Budi Santoso',
        'rute'      => 'Purbaya → Terminal Rajekwesi',
        'bus'       => 'MyHalte Express',
        'kursi'     => 'C2, C3',
        'total'     => 60000,
        'status'    => 'Lunas',
        'waktu'     => '1 jam lalu',
    ],
    [
        'id'        => 'MHT-8924',
        'nama'      => 'Dewi Lestari',
        'rute'      => 'Alun-Alun → Stasiun Madiun',
        'bus'       => 'MyHalte Kota',
        'kursi'     => 'D1',
        'total'     => 15000,
        'status'    => 'Batal',
        'waktu'     => '3 jam lalu',
    ],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MyHalte</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-50 text-gray-900 font-sans antialiased min-h-screen flex">

    <!-- Sidebar Navigasi -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col shrink-0 min-h-screen">
        <div class="p-6 flex items-center gap-3 border-b border-gray-100">
            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-md">
                <i data-lucide="bus" class="w-6 h-6"></i>
            </div>
            <div>
                <h2 class="font-bold text-lg leading-tight">MyHalte</h2>
                <span class="text-xs text-gray-500 font-medium tracking-wider uppercase">Portal Admin</span>
            </div>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1.5">
            <a href="AdminDashboard.blade.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-blue-50 text-blue-600 font-semibold text-sm transition">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Dashboard
            </a>
            <a href="OrderHistory.blade.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-100 font-medium text-sm transition">
                <i data-lucide="ticket" class="w-5 h-5"></i>
                Pesanan Tiket
            </a>
            <a href="Search.blade.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-100 font-medium text-sm transition">
                <i data-lucide="bus" class="w-5 h-5"></i>
                Armada & Jadwal
            </a>
            <a href="Profile.blade.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-100 font-medium text-sm transition">
                <i data-lucide="users" class="w-5 h-5"></i>
                Data Penumpang
            </a>
        </nav>

        <div class="p-4 border-t border-gray-100">
            <a href="Login.blade.php" onclick="return confirm('Keluar dari portal Admin?');" class="flex items-center gap-3 px-3 py-2 rounded-xl text-red-600 hover:bg-red-50 text-sm font-semibold transition">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                Keluar
            </a>
        </div>
    </aside>

    <!-- Konten Utama Dashboard -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-gray-200 px-8 flex items-center justify-between sticky top-0 z-10">
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span>Admin</span>
                <span>/</span>
                <span class="text-gray-900 font-semibold">Ringkasan Operasional</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                    AD
                </div>
                <span class="text-sm font-semibold text-gray-800">Administrator</span>
            </div>
        </header>

        <main class="p-8 max-w-7xl w-full mx-auto space-y-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Ringkasan Performa</h1>
                <p class="text-sm text-gray-500 mt-1">Pantau transaksi tiket dan operasional halte terkini.</p>
            </div>

            <!-- Kartu Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-xs font-semibold uppercase text-gray-400">Total Pendapatan</span>
                        <div class="p-2 rounded-xl bg-green-50 text-green-600">
                            <i data-lucide="trending-up" class="w-5 h-5"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">Rp <?= number_format($stats->total_pendapatan, 0, ',', '.') ?></div>
                    <p class="text-xs text-green-600 mt-2 font-medium">+12.5% dari bulan lalu</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-xs font-semibold uppercase text-gray-400">Tiket Terjual</span>
                        <div class="p-2 rounded-xl bg-blue-50 text-blue-600">
                            <i data-lucide="ticket" class="w-5 h-5"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900"><?= number_format($stats->total_tiket, 0, ',', '.') ?></div>
                    <p class="text-xs text-gray-500 mt-2 font-medium">Transaksi terkonfirmasi</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-xs font-semibold uppercase text-gray-400">Armada Beroperasi</span>
                        <div class="p-2 rounded-xl bg-amber-50 text-amber-600">
                            <i data-lucide="bus" class="w-5 h-5"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900"><?= $stats->total_bus ?> Bus</div>
                    <p class="text-xs text-amber-600 mt-2 font-medium">Seluruh armada prima</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-xs font-semibold uppercase text-gray-400">Pengguna Aktif</span>
                        <div class="p-2 rounded-xl bg-purple-50 text-purple-600">
                            <i data-lucide="users" class="w-5 h-5"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900"><?= $stats->pengguna_aktif ?></div>
                    <p class="text-xs text-purple-600 mt-2 font-medium">Akun penumpang aktif</p>
                </div>
            </div>

            <!-- Tabel Transaksi Terbaru -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-900">Pesanan Tiket Masuk</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Daftar transaksi pemesanan terbaru oleh pelanggan</p>
                    </div>
                    <a href="OrderHistory.blade.php" class="px-3.5 py-2 text-xs font-semibold rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                        Lihat Seluruhnya
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-100">
                            <tr>
                                <th class="py-3 px-6">ID Booking</th>
                                <th class="py-3 px-6">Penumpang</th>
                                <th class="py-3 px-6">Rute & Bus</th>
                                <th class="py-3 px-6">Kursi</th>
                                <th class="py-3 px-6">Total Tagihan</th>
                                <th class="py-3 px-6">Status</th>
                                <th class="py-3 px-6 text-right">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($recentOrders as $order): ?>
                                <tr class="hover:bg-gray-50/75 transition">
                                    <td class="py-4 px-6 font-semibold text-blue-600"><?= $order['id'] ?></td>
                                    <td class="py-4 px-6 font-medium text-gray-900"><?= htmlspecialchars($order['nama']) ?></td>
                                    <td class="py-4 px-6">
                                        <div class="font-medium text-gray-800"><?= htmlspecialchars($order['rute']) ?></div>
                                        <div class="text-xs text-gray-400"><?= htmlspecialchars($order['bus']) ?></div>
                                    </td>
                                    <td class="py-4 px-6 font-semibold text-gray-700"><?= $order['kursi'] ?></td>
                                    <td class="py-4 px-6 font-semibold text-gray-900">Rp <?= number_format($order['total'], 0, ',', '.') ?></td>
                                    <td class="py-4 px-6">
                                        <?php if ($order['status'] === 'Lunas'): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                                Lunas
                                            </span>
                                        <?php elseif ($order['status'] === 'Menunggu'): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                Menunggu
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                                Batal
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-4 px-6 text-right text-xs text-gray-500"><?= $order['waktu'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>