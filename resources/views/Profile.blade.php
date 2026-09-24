<?php
// Profile.blade.php - Halaman Profil Saya My Halte (Blade / PHP Template)

// Tangkap Parameter Edit State
$isEditing = isset($_GET['edit']) && $_GET['edit'] == '1';
$successMessage = '';
$errorMessage = '';

// Sample User Data (Dapat bersumber dari $_SESSION / Database)
$user = [
    'id' => 'USR-882910',
    'name' => 'Budi Santoso',
    'email' => 'budi.santoso@example.com',
    'phone' => '08123456789',
    'role' => 'Member'
];

// Proses Update Form (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = isset($_POST['name']) ? trim($_POST['name']) : '';
    $newEmail = isset($_POST['email']) ? trim($_POST['email']) : '';
    $newPhone = isset($_POST['phone']) ? trim($_POST['phone']) : '';

    if (empty($newName) || empty($newEmail) || empty($newPhone)) {
        $errorMessage = 'Semua field profil harus diisi!';
        $isEditing = true;
    } elseif (strpos($newEmail, '@') === false) {
        $errorMessage = 'Format email tidak valid!';
        $isEditing = true;
    } else {
        $user['name'] = $newName;
        $user['email'] = $newEmail;
        $user['phone'] = $newPhone;
        $successMessage = 'Profil berhasil diperbarui!';
        $isEditing = false;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Halte - Profil Saya</title>
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
                <h1 class="text-xl font-bold text-gray-900">Profil Saya</h1>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6 max-w-2xl">
        
        <!-- Flash Messages -->
        <?php if (!empty($successMessage)): ?>
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 p-4 rounded-2xl text-sm flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span><?= htmlspecialchars($successMessage) ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div class="mb-6 bg-red-50 border border-red-200 text-red-600 p-4 rounded-2xl text-sm flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span><?= htmlspecialchars($errorMessage) ?></span>
            </div>
        <?php endif; ?>

        <!-- Profile Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-6">
            
            <!-- User Avatar Circle -->
            <div class="flex items-center justify-center mb-6">
                <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center shadow-inner">
                    <!-- User Icon -->
                    <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>

            <!-- Profile Form -->
            <form method="POST" action="Profile.blade.php" class="space-y-4">
                
                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <?php if ($isEditing): ?>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="<?= htmlspecialchars($user['name']) ?>"
                            required
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        />
                    <?php else: ?>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100 text-gray-800 text-sm">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-semibold text-gray-900"><?= htmlspecialchars($user['name']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <?php if ($isEditing): ?>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?= htmlspecialchars($user['email']) ?>"
                            required
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        />
                    <?php else: ?>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100 text-gray-800 text-sm">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-semibold text-gray-900"><?= htmlspecialchars($user['email']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1">Nomor Telepon</label>
                    <?php if ($isEditing): ?>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="<?= htmlspecialchars($user['phone']) ?>"
                            required
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        />
                    <?php else: ?>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100 text-gray-800 text-sm">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="font-semibold text-gray-900"><?= htmlspecialchars($user['phone']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Action Buttons -->
                <?php if ($isEditing): ?>
                    <div class="flex gap-3 pt-4">
                        <button
                            type="submit"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-xl shadow transition flex items-center justify-center"
                        >
                            <!-- Save Icon -->
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Simpan Perubahan
                        </button>
                        <a
                            href="Profile.blade.php"
                            class="flex-1 bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 font-semibold py-2.5 px-4 rounded-xl flex items-center justify-center transition shadow-sm"
                        >
                            Batal
                        </a>
                    </div>
                <?php else: ?>
                    <div class="pt-2">
                        <a
                            href="Profile.blade.php?edit=1"
                            class="w-full bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 font-semibold py-2.5 px-4 rounded-xl flex items-center justify-center transition shadow-sm"
                        >
                            <!-- Edit Icon -->
                            <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Profil
                        </a>
                    </div>
                <?php endif; ?>

            </form>
        </div>

        <!-- Account Info Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-4 text-base">Informasi Akun</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between py-1 border-b border-gray-50">
                    <span class="text-gray-500">ID Pengguna:</span>
                    <span class="font-semibold text-gray-900 font-mono"><?= htmlspecialchars($user['id']) ?></span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-gray-500">Tipe Akun:</span>
                    <span class="font-semibold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider">
                        <?= htmlspecialchars($user['role']) ?>
                    </span>
                </div>
            </div>
        </div>

    </main>

</body>
</html>
