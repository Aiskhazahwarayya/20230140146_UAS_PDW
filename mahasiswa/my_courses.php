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

// ------ LOGIKA BARU UNTUK MEMBATALKAN PENDAFTARAN (DELETE) ------
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['batal_daftar'])) {
    $praktikum_id_to_delete = $_POST['praktikum_id'];

    $stmt = $conn->prepare("DELETE FROM pendaftaran_praktikum WHERE mahasiswa_id = ? AND praktikum_id = ?");
    $stmt->bind_param("ii", $mahasiswa_id, $praktikum_id_to_delete);
    if ($stmt->execute()) {
        $message = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert"><p class="font-bold">Informasi</p><p>Pendaftaran telah dibatalkan.</p></div>';
    } else {
        $message = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert"><p class="font-bold">Error</p><p>Gagal membatalkan pendaftaran.</p></div>';
    }
    $stmt->close();
}


// ------ Definisi Variabel untuk Template ------
$pageTitle = 'Praktikum Saya';
$activePage = 'my_courses';
require_once 'templates/header_mahasiswa.php';
?>

<!-- Tampilkan pesan notifikasi jika ada -->
<?php echo $message; ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    <?php
    // Query untuk mengambil data praktikum yang diikuti oleh mahasiswa ini.
    $sql = "SELECT mp.id, mp.nama_praktikum, mp.deskripsi 
            FROM pendaftaran_praktikum pp
            JOIN mata_praktikum mp ON pp.praktikum_id = mp.id
            WHERE pp.mahasiswa_id = ?
            ORDER BY mp.nama_praktikum ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $mahasiswa_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0):
        while($row = $result->fetch_assoc()):
    ?>
    <!-- Tampilkan setiap praktikum yang diikuti sebagai sebuah kartu (card) -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col">
        <div class="p-6 flex-grow">
            <h3 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($row['nama_praktikum']); ?></h3>
            <p class="text-gray-600 text-base mb-6">
                <?php echo htmlspecialchars($row['deskripsi']); ?>
            </p>
        </div>
        <div class="p-6 pt-0">
             <!-- Tombol Lihat Detail -->
            <a href="course_detail.php?id=<?php echo $row['id']; ?>" class="block text-center w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-300 mb-3">
                Lihat Detail & Tugas
            </a>
            <!-- Form untuk Batal Daftar -->
            <form action="my_courses.php" method="POST" onsubmit="return confirm('Anda yakin ingin membatalkan pendaftaran dari praktikum ini?');">
                <input type="hidden" name="praktikum_id" value="<?php echo $row['id']; ?>">
                <button type="submit" name="batal_daftar" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-300">
                    Batalkan Pendaftaran
                </button>
            </form>
        </div>
    </div>
    <?php
        endwhile;
    else:
    ?>
    <!-- Tampilkan pesan ini jika mahasiswa belum mendaftar di praktikum manapun -->
    <div class="col-span-3 bg-white p-6 rounded-lg shadow-md text-center">
        <p class="text-gray-700">Anda belum terdaftar di mata praktikum manapun.</p>
        <a href="courses.php" class="text-blue-600 hover:underline mt-2 inline-block">Cari praktikum untuk diikuti.</a>
    </div>
    <?php
    endif;
    $stmt->close();
    $conn->close();
    ?>
</div>

<?php
require_once 'templates/footer_mahasiswa.php';
?>
