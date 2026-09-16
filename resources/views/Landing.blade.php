<?php
// Session atau logika PHP awal (jika sudah login, bisa langsung redirect ke home.php)
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyHalte - Pemesanan Tiket Bus Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased min-h-screen flex flex-col justify-between">

    <!-- ================= NAVBAR ================= -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="bg-blue-600 text-white p-2 rounded-xl flex items-center justify-center shadow-sm">
                    <i data-lucide="bus" class="w-6 h-6"></i>
                </div>
                <span class="font-bold text-xl text-blue-600 tracking-tight">MyHalte</span>
            </div>

            <div class="flex items-center gap-3">
                <a href="login.php" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 transition">
                    Masuk
                </a>
                <a href="register.php" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition">
                    Daftar
                </a>
            </div>
        </div>
    </header>

    <!-- ================= HERO SECTION ================= -->
    <main class="flex-grow">
        <section class="relative py-16 lg:py-24 bg-gradient-to-b from-blue-50/60 to-white overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold mb-6">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                        Solusi Perjalanan Bus Terintegrasi
                    </div>
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 tracking-tight leading-tight mb-6">
                        Pesan Tiket Bus Antar Halte <br class="hidden sm:inline" />
                        <span class="text-blue-600">Mudah, Cepat, & Nyaman</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        Nikmati kemudahan pemesanan tiket bus secara real-time, pilih nomor kursi favorit Anda, dan dapatkan E-Ticket tanpa antre di loket.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="search.php" class="w-full sm:w-auto px-8 py-3.5 text-base font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md hover:shadow-lg transition flex items-center justify-center gap-2">
                            <i data-lucide="search" class="w-5 h-5"></i>
                            Cari Tiket Sekarang
                        </a>
                        <a href="login.php" class="w-full sm:w-auto px-8 py-3.5 text-base font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-xl transition flex items-center justify-center">
                            Masuk ke Akun
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= FITUR UNGGULAN ================= -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Kenapa Memilih MyHalte?</h2>
                    <p class="text-gray-500 mt-2 text-sm sm:text-base">Pengalaman bepergian antar halte yang dirancang untuk kenyamanan Anda.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Kartu 1 -->
                    <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-md transition">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-4">
                            <i data-lucide="map-pin" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Rute Halte Lengkap</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Akses ke seluruh titik rute dan halte transit dengan pembaruan jadwal yang akurat setiap hari.
                        </p>
                    </div>

                    <!-- Kartu 2 -->
                    <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-md transition">
                        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-4">
                            <i data-lucide="armchair" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Pilih Kursi Sendiri</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Denah kursi bus interaktif memungkinkan Anda memilih posisi tempat duduk yang paling nyaman.
                        </p>
                    </div>

                    <!-- Kartu 3 -->
                    <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-md transition">
                        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mb-4">
                            <i data-lucide="ticket" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">E-Ticket & QR Code</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Tidak perlu cetak kertas. Cukup tunjukkan kode QR pada E-Ticket dari ponsel Anda kepada petugas.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-gray-900 text-gray-400 py-10 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <i data-lucide="bus" class="w-5 h-5 text-blue-500"></i>
                <span class="text-white font-semibold tracking-wide">MyHalte</span>
            </div>
            <p class="text-sm text-center sm:text-right">
                &copy; <?php echo date('Y'); ?> MyHalte. Hak Cipta Dilindungi.
            </p>
        </div>
    </footer>

    <!-- Inisialisasi Ikon Lucide -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>