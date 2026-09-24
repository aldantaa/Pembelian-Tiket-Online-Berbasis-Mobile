<?php
// Login.blade.php - Halaman Login My Halte (Blade / PHP Template)

$error = '';
$email = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = 'Email dan password harus diisi';
    } elseif (!str_contains($email, '@')) {
        $error = 'Format email tidak valid';
    } else {
        // Logika login & redirect sesuai role
        if ($email === 'admin@myhalte.com') {
            header('Location: AdminDashboard.blade.php');
            echo "<script>window.location.href='AdminDashboard.blade.php';</script>";
            exit;
        } else {
            header('Location: Home.blade.php');
            echo "<script>window.location.href='Home.blade.php';</script>";
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Halte - Login</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        
        <!-- Back Link -->
        <a href="Home.blade.php" class="inline-flex items-center mb-4 text-gray-700 hover:text-blue-600 transition font-medium text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Beranda
        </a>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
            
            <!-- Brand Logo -->
            <div class="flex items-center justify-center gap-2 mb-6 cursor-pointer" onclick="window.location.href='Home.blade.php'">
                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8m-8 4h8m-5 4h2M5 3h14a2 2 0 012 2v11a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2zM5 19v2m14-2v2"></path>
                </svg>
                <h1 class="text-3xl font-bold text-blue-600">My Halte</h1>
            </div>

            <h2 class="text-2xl font-bold text-center text-gray-900 mb-1">Masuk</h2>
            <p class="text-center text-gray-600 text-sm mb-6">
                Masukkan email dan password Anda
            </p>

            <form method="POST" action="Login.blade.php" class="space-y-4">
                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            placeholder="nama@email.com"
                            value="<?= htmlspecialchars($email) ?>"
                            required
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        >
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            placeholder="••••••••"
                            value="<?= htmlspecialchars($password) ?>"
                            required
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        >
                    </div>
                </div>

                <!-- Error Message -->
                <?php if (!empty($error)): ?>
                    <div class="bg-red-50 border border-red-200 text-red-600 p-3 rounded-xl text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span><?= htmlspecialchars($error) ?></span>
                    </div>
                <?php endif; ?>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl shadow transition">
                    Masuk
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm">
                    Belum punya akun?
                    <a href="Register.blade.php" class="text-blue-600 font-semibold hover:underline">
                        Daftar Sekarang
                    </a>
                </p>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-100">
                <div class="bg-slate-50 p-3 rounded-xl text-xs text-gray-500 text-center leading-relaxed">
                    <strong>Demo Login:</strong><br>
                    Customer: Gunakan email apapun<br>
                    Admin: <code class="text-blue-600 font-bold">admin@myhalte.com</code>
                </div>
            </div>
        </div>

    </div>

</body>
</html>