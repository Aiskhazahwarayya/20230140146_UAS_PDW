<?php
// Pengaturan Database
define('DB_SERVER', '127.0.0.1');      // atau bisa pakai 'localhost'
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', ''); 
define('DB_NAME', 'simprakt_uas');
define('DB_PORT', 3308);               // tambahkan port 3308 karena MySQL XAMPP kamu jalan di sini

// Membuat koneksi ke database dengan menambahkan parameter port
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}
?>
