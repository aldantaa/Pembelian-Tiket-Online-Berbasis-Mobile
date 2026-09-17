<?php
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
        // Contoh logika login
        // arahkan ke halaman sesuai role
        if ($email === 'admin@myhalte.com') {
            header('Location: admin.php');
            exit;
        } else {
            header('Location: home.php');
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
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <a href="javascript:history.back()" class="inline-flex items-center mb-4 text-gray-700 hover:text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="flex items-center justify-center gap-2 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8l1 9H7l1-9zm2 0V5a2 2 0 114 0v2M5 10h14" />
                </svg>
                <h1 class="text-3xl font-bold text-blue-600">My Halte</h1>
            </div>

            <h2 class="text-2xl font-bold text-center mb-2">Masuk</h2>
            <p class="text-center text-gray-600 mb-6">
                Masukkan email dan password Anda
            </p>

            <form method="POST" action="" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-3 size-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-18 0v1.5a2.5 2.5 0 005 0V12" />
                        </svg>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            placeholder="nama@email.com"
                            value="<?= htmlspecialchars($email) ?>"
                            class="w-full pl-10 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-3 size-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11V7a4 4 0 118 0v4M5 11h14a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2v-7a2 2 0 012-2z" />
                        </svg>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            placeholder="••••••••"
                            value="<?= htmlspecialchars($password) ?>"
                            class="w-full pl-10 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl">
                    Masuk
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm">
                    Belum punya akun?
                    <a href="register.php" class="text-blue-600 font-semibold hover:underline">
                        Daftar Sekarang
                    </a>
                </p>
            </div>

            <div class="mt-6 pt-6 border-t">
                <p class="text-xs text-gray-500 text-center">
                    Demo: <br>
                    Customer: gunakan email apapun<br>
                    Admin: admin@myhalte.com
                </p>
            </div>
        </div>
    </div>
</body>
</html>