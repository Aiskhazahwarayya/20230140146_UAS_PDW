<?php
// Selalu mulai session di awal
session_start();

// Hapus semua data dari variabel session
$_SESSION = array();

// Hancurkan session secara permanen
session_destroy();

// Arahkan pengguna kembali ke halaman login.
// Path ini sudah benar karena logout.php dan login.php berada di folder yang sama.
header("Location: login.php");
exit;
?><?php
// Selalu mulai session di awal
session_start();

// Hapus semua data dari variabel session
$_SESSION = array();

// Hancurkan session secara permanen
session_destroy();

// ------ LOGIKA BARU UNTUK REDIRECT YANG LEBIH BAIK ------

// 1. Tentukan protokol (http atau https)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

// 2. Dapatkan nama host (misal: localhost)
$host = $_SERVER['HTTP_HOST'];

// 3. Dapatkan path dasar dari folder proyek (misal: /UAS_PDW)
$base_path = dirname($_SERVER['SCRIPT_NAME']);
if ($base_path == '/' || $base_path == '\\') {
    $base_path = ''; // Kosongkan jika di root directory
}

// 4. Gabungkan semuanya menjadi URL yang lengkap
$redirect_url = $protocol . $host . $base_path . '/login.php';


// 5. Arahkan pengguna ke URL yang sudah lengkap dan benar
header("Location: " . $redirect_url);
exit;
?>
