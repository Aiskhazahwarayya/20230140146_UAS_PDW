<?php
require_once 'config.php';

$message = '';
$message_type = 'error'; // Default message type is error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    // Validasi sederhana
    if (empty($nama) || empty($email) || empty($password) || empty($role)) {
        $message = "Semua field harus diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Format email tidak valid!";
    } elseif (!in_array($role, ['mahasiswa', 'asisten'])) {
        $message = "Peran tidak valid!";
    } else {
        // Cek apakah email sudah terdaftar
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Email sudah terdaftar. Silakan gunakan email lain.";
        } else {
            // Hash password untuk keamanan
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Simpan ke database
            $sql_insert = "INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ssss", $nama, $email, $hashed_password, $role);

            if ($stmt_insert->execute()) {
                // Redirect ke halaman login dengan status sukses
                header("Location: login.php?status=registered");
                exit();
            } else {
                $message = "Terjadi kesalahan saat registrasi. Silakan coba lagi.";
            }
            $stmt_insert->close();
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - SIMPRAK</title>
    <!-- Memuat Tailwind CSS dari CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Memuat Font Inter dari Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 p-4">
        <div class="w-full max-w-md">
            <div class="bg-white p-8 md:p-10 rounded-2xl shadow-xl">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-blue-600">Buat Akun Baru</h1>
                    <p class="text-gray-500 mt-2">Daftar untuk mulai menggunakan SIMPRAK</p>
                </div>

                <!-- Menampilkan Pesan Notifikasi Error -->
                <?php 
                if (!empty($message)) {
                    echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg" role="alert"><p class="font-bold">Registrasi Gagal</p><p>' . htmlspecialchars($message) . '</p></div>';
                }
                ?>
                
                <form action="register.php" method="post" class="space-y-5">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan nama lengkap" required>
                    </div>
                     <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="contoh@email.com" required>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Buat password yang kuat" required>
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Daftar Sebagai</label>
                        <select id="role" name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="mahasiswa">Mahasiswa</option>
                            <option value="asisten">Asisten</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition-transform transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Daftar
                        </button>
                    </div>
                </form>
                
                <div class="text-center text-sm text-gray-600 mt-8">
                    <p>Sudah punya akun? <a href="login.php" class="font-medium text-blue-600 hover:text-blue-500">Login di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
