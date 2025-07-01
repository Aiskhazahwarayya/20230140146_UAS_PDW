<?php
// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek jika pengguna belum login atau bukan mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Mahasiswa - <?php echo $pageTitle ?? 'Dashboard'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">

<nav class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-2xl font-bold text-blue-600">SIMPRAK</span>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <?php 
                            $activeClass = 'bg-blue-100 text-blue-600';
                            $inactiveClass = 'text-gray-500 hover:bg-gray-100 hover:text-gray-900';
                        ?>
                        <a href="dashboard.php" class="<?php echo ($activePage == 'dashboard') ? $activeClass : $inactiveClass; ?> px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                        <a href="my_courses.php" class="<?php echo ($activePage == 'my_courses') ? $activeClass : $inactiveClass; ?> px-3 py-2 rounded-md text-sm font-medium">Praktikum Saya</a>
                        <a href="courses.php" class="<?php echo ($activePage == 'courses') ? $activeClass : $inactiveClass; ?> px-3 py-2 rounded-md text-sm font-medium">Cari Praktikum</a>
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    <!-- PERBAIKAN DI SINI: href diubah ke ../logout.php -->
                    <a href="../logout.php" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md transition-colors duration-300">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="container mx-auto p-6 lg:p-8">
