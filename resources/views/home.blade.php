<?php
// homepage.php - Halaman Utama My Halte (PHP Version)

// Sample User Data (Dapat diambil dari $_SESSION atau Database)
$user = [
    'id' => 1,
    'name' => 'Budi Santoso',
    'email' => 'budi.santoso@example.com'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Halte - Halaman Utama</title>
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
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2 cursor-pointer" onclick="window.location.href='homepage.php'">
                    <!-- Bus Icon -->
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8m-8 4h8m-5 4h2M5 3h14a2 2 0 012 2v11a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2zM5 19v2m14-2v2"></path>
                    </svg>
                    <h1 class="text-2xl font-bold text-blue-600">My Halte</h1>
                </div>
                
                <div class="flex items-center gap-2">
                    <!-- Profile Button -->
                    <a href="profile.php" title="Profil Saya" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-slate-100 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </a>
                    
                    <!-- History Button -->
                    <a href="order_history.php" title="Riwayat Pesanan" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-slate-100 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </a>
                    
                    <!-- Logout Button -->
                    <a href="login.php" title="Logout" onclick="return confirm('Apakah Anda yakin ingin keluar?');" class="p-2 text-gray-600 hover:text-red-600 hover:bg-slate-100 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-2 text-gray-800">
                Selamat Datang, <?= htmlspecialchars($user['name']) ?>!
            </h2>
            <p class="text-gray-600">
                Mau ke mana hari ini? Cari jadwal bus favoritmu
            </p>
        </div>

        <!-- Search Banner Card -->
        <div class="mb-8 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl p-6 shadow-md border-0">
            <h3 class="text-xl font-bold mb-4">Cari Jadwal Bus</h3>
            <a href="search.php" class="w-full bg-white text-blue-600 hover:bg-gray-100 font-semibold py-3 px-4 rounded-xl flex items-center justify-center transition shadow">
                <!-- Search Icon -->
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Cari Perjalanan
            </a>
        </div>

        <!-- Shortcut Action Cards -->
        <div class="grid md:grid-cols-2 gap-4 mb-8">
            
            <!-- Order History Card -->
            <a href="order_history.php" class="bg-white rounded-2xl border border-gray-100 p-6 flex items-center gap-4 hover:shadow-lg transition cursor-pointer group">
                <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center shrink-0 group-hover:bg-blue-600 transition">
                    <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 group-hover:text-blue-600 transition">Riwayat Pesanan</h4>
                    <p class="text-sm text-gray-600">Lihat tiket yang sudah dipesan</p>
                </div>
            </a>

            <!-- Profile Card -->
            <a href="profile.php" class="bg-white rounded-2xl border border-gray-100 p-6 flex items-center gap-4 hover:shadow-lg transition cursor-pointer group">
                <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center shrink-0 group-hover:bg-blue-600 transition">
                    <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 group-hover:text-blue-600 transition">Profil Saya</h4>
                    <p class="text-sm text-gray-600">Kelola informasi akun</p>
                </div>
            </a>

        </div>

    </main>

</body>
</html>
