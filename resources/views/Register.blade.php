<?php
session_start();

// Hubungkan ke file koneksi jika sudah ada
if (file_exists('koneksi.php')) {
    require_once 'koneksi.php';
}

$pesan_error = '';
$pesan_sukses = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = isset($_POST['nama']) ? trim($_POST['nama']) : '';
    $email    = isset($_POST['email']) ? trim($_POST['email']) : '';
    $no_hp    = isset($_POST['no_hp']) ? trim($_POST['no_hp']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($nama) || empty($email) || empty($no_hp) || empty($password)) {
        $pesan_error = "Harap isi semua kolom formulir!";
    } elseif (isset($conn)) {
        $nama_esc  = mysqli_real_escape_string($conn, $nama);
        $email_esc = mysqli_real_escape_string($conn, $email);
        $no_hp_esc = mysqli_real_escape_string($conn, $no_hp);
        $hash_pass = password_hash($password, PASSWORD_DEFAULT);

        // Cek duplikasi email
        $cek_email = mysqli_query($conn, "SELECT id_user FROM users WHERE email = '$email_esc'");
        if (mysqli_num_rows($cek_email) > 0) {
            $pesan_error = "Email sudah digunakan, gunakan email lain.";
        } else {
            $sql = "INSERT INTO users (nama, email, no_hp, password, role) VALUES ('$nama_esc', '$email_esc', '$no_hp_esc', '$hash_pass', 'penumpang')";
            if (mysqli_query($conn, $sql)) {
                header("Location: login.php?pesan=registrasi_berhasil");
                exit;
            } else {
                $pesan_error = "Terjadi kesalahan sistem, silakan coba lagi.";
            }
        }
    } else {
        // Fallback jika belum membuat tabel/koneksi database
        $pesan_sukses = "Akun berhasil didaftarkan (Mode Pratinjau tanpa database).";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - MyHalte</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
        
        <!-- Header & Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-xl shadow-md mb-4">
                <i data-lucide="bus" class="w-6 h-6"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Buat Akun MyHalte</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar untuk kemudahan pemesanan tiket bus</p>
        </div>

        <!-- Notifikasi Error / Sukses -->
        <?php if (!empty($pesan_error)): ?>
            <div class="mb-5 flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 text-sm p-3.5 rounded-xl">
                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                <span><?= htmlspecialchars($pesan_error) ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($pesan_sukses)): ?>
            <div class="mb-5 flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 text-sm p-3.5 rounded-xl">
                <i data-lucide="check-circle-2" class="w-4 h-4 shrink-0"></i>
                <span><?= htmlspecialchars($pesan_sukses) ?></span>
            </div>
        <?php endif; ?>

        <!-- Formulir Pendaftaran -->
        <form action="register.php" method="POST" class="space-y-4">
            
            <!-- Nama Lengkap -->
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </span>
                    <input type="text" id="nama" name="nama" required placeholder="Nama Lengkap Anda"
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                    </span>
                    <input type="email" id="email" name="email" required placeholder="nama@email.com"
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
            </div>

            <!-- Nomor Telepon / WhatsApp -->
            <div>
                <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Handphone</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="phone" class="w-4 h-4"></i>
                    </span>
                    <input type="tel" id="no_hp" name="no_hp" required placeholder="08xxxxxxxxxx"
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                    </span>
                    <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter"
                        class="w-full pl-10 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i data-lucide="eye" id="iconEye" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <!-- Tombol Submit -->
            <button type="submit" class="w-full mt-2 py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition flex items-center justify-center gap-2">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                Daftar Sekarang
            </button>
        </form>

        <!-- Navigasi ke Halaman Login -->
        <div class="mt-6 text-center text-sm text-gray-500">
            Sudah memiliki akun? 
            <a href="login.php" class="font-medium text-blue-600 hover:text-blue-700 transition">
                Masuk di sini
            </a>
        </div>
    </div>

    <script>
        // Inisialisasi ikon
        lucide.createIcons();

        // Fitur intip password
        function togglePasswordVisibility() {
            const inputPass = document.getElementById('password');
            const iconEye = document.getElementById('iconEye');
            if (inputPass.type === 'password') {
                inputPass.type = 'text';
                iconEye.setAttribute('data-lucide', 'eye-off');
            } else {
                inputPass.type = 'password';
                iconEye.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
    </script>
</body>
</html>