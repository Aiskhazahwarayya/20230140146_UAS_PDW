<?php
// Memanggil file config untuk koneksi database dan memulai session
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pastikan hanya mahasiswa yang bisa mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

$mahasiswa_id = $_SESSION['user_id'];
$message = '';

// ------ LOGIKA UNTUK PROSES PENDAFTARAN (CREATE) ------
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['daftar'])) {
    $praktikum_id = $_POST['praktikum_id'];

    // Cek dulu apakah sudah terdaftar
    $check_stmt = $conn->prepare("SELECT id FROM pendaftaran_praktikum WHERE mahasiswa_id = ? AND praktikum_id = ?");
    $check_stmt->bind_param("ii", $mahasiswa_id, $praktikum_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO pendaftaran_praktikum (mahasiswa_id, praktikum_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $mahasiswa_id, $praktikum_id);
        if ($stmt->execute()) {
            $message = '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert"><p class="font-bold">Sukses</p><p>Pendaftaran berhasil!</p></div>';
        }
        $stmt->close();
    }
    $check_stmt->close();
}

// ------ LOGIKA BARU UNTUK MEMBATALKAN PENDAFTARAN (DELETE) ------
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['batal_daftar'])) {
    $praktikum_id = $_POST['praktikum_id'];

    $stmt = $conn->prepare("DELETE FROM pendaftaran_praktikum WHERE mahasiswa_id = ? AND praktikum_id = ?");
    $stmt->bind_param("ii", $mahasiswa_id, $praktikum_id);
    if ($stmt->execute()) {
        $message = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert"><p class="font-bold">Informasi</p><p>Pendaftaran telah dibatalkan.</p></div>';
    }
    $stmt->close();
}


// Ambil semua praktikum yang sudah diikuti mahasiswa untuk pengecekan
$registered_courses = [];
$stmt_registered = $conn->prepare("SELECT praktikum_id FROM pendaftaran_praktikum WHERE mahasiswa_id = ?");
$stmt_registered->bind_param("i", $mahasiswa_id);
$stmt_registered->execute();
$result_registered = $stmt_registered->get_result();
while ($reg_row = $result_registered->fetch_assoc()) {
    $registered_courses[] = $reg_row['praktikum_id'];
}
$stmt_registered->close();


// ------ Definisi Variabel untuk Template ------
$pageTitle = 'Cari Praktikum';
$activePage = 'courses';
require_once 'templates/header_mahasiswa.php';
?>

<!-- Tampilkan pesan notifikasi (sukses/error/peringatan) jika ada -->
<?php echo $message; ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    <?php
    // Ambil semua data mata praktikum yang tersedia dari database
    $result = $conn->query("SELECT * FROM mata_praktikum ORDER BY nama_praktikum ASC");
    if ($result && $result->num_rows > 0):
        while($row = $result->fetch_assoc()):
            $is_registered = in_array($row['id'], $registered_courses);
    ?>
    <!-- Tampilkan setiap praktikum sebagai sebuah kartu (card) -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:-translate-y-1 transition-transform duration-300">
        <div class="p-6 flex flex-col h-full">
            <h3 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($row['nama_praktikum']); ?></h3>
            <p class="text-gray-600 text-base mb-6 flex-grow">
                <?php echo htmlspecialchars($row['deskripsi']); ?>
            </p>
            
            <!-- Form akan berubah tergantung status pendaftaran -->
            <form action="courses.php" method="POST">
                <input type="hidden" name="praktikum_id" value="<?php echo $row['id']; ?>">
                <?php if ($is_registered): ?>
                    <!-- Tombol untuk Batal Daftar -->
                    <button type="submit" name="batal_daftar" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-300">
                        Batalkan Pendaftaran
                    </button>
                <?php else: ?>
                    <!-- Tombol untuk Daftar -->
                    <button type="submit" name="daftar" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-300">
                        Daftar Praktikum
                    </button>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <?php
        endwhile;
    else:
    ?>
    <!-- Tampilkan pesan ini jika tidak ada praktikum yang dibuat oleh asisten -->
    <div class="col-span-3 bg-white p-6 rounded-lg shadow-md text-center">
        <p class="text-gray-700">Belum ada mata praktikum yang tersedia saat ini. Silakan cek kembali nanti.</p>
    </div>
    <?php
    endif;
    $conn->close();
    ?>
</div>

<?php
require_once 'templates/footer_mahasiswa.php';
?>
