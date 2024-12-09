<?php
session_start();

// Koneksi ke database
include('../koneksi.php');

// Fungsi untuk generate no_rm (format: YYYYMM-XXX)
function generateNoRM($conn) {
    // Mendapatkan tahun dan bulan sekarang
    $year_month = date("Ym");
    
    // Query untuk mencari ID terakhir pasien
    $sql = "SELECT id FROM pasien ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);
    
    // Jika ada data pasien sebelumnya
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastId = $row['id'];
        // Menentukan urutan no_rm berdasarkan ID terakhir
        $sequence = sprintf("%03d", $lastId + 1);
    } else {
        // Jika belum ada pasien, urutan pertama adalah 001
        $sequence = "001";
    }

    // Menghasilkan no_rm dengan format YYYYMM-XXX
    return $year_month . "-" . $sequence;
}

// Cek apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pastikan input ada di $_POST sebelum mengaksesnya
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
    $ktp = isset($_POST['noktp']) ? $_POST['noktp'] : '';
    $nohp = isset($_POST['nohp']) ? $_POST['nohp'] : '';
    
    // Mengecek apakah pasien sudah terdaftar
    $sql = "SELECT * FROM pasien WHERE nama = ? OR no_ktp = ? OR no_hp = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $ktp, $nohp);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika pasien sudah terdaftar
    if ($result->num_rows > 0) {
        $error_message = "Pasien dengan nama, no KTP atau no HP ini sudah terdaftar.";
    } else {
        // Jika pasien belum terdaftar, proses registrasi
        $no_rm = generateNoRM($conn); // Generate no_rm otomatis

        // Menyimpan data pasien baru ke database
        $sql = "INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $username, $alamat, $ktp, $nohp, $no_rm);
        
        if ($stmt->execute()) {
            // Jika berhasil, tampilkan no_rm di pesan sukses
            $success_message = "Registrasi berhasil! No RM Anda adalah: " . $no_rm;
            $_SESSION['no_rm'] = $no_rm; // Menyimpan no_rm ke session
        } else {
            $error_message = "Terjadi kesalahan saat registrasi. Silakan coba lagi.";
        }
    }

    // Menutup statement
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pasien</title>
    <!-- Link ke Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Container untuk form registrasi -->
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card shadow-lg" style="max-width: 400px; width: 100%;">
            <div class="card-body">
                <h2 class="card-title text-center text-primary mb-4">Registrasi Pasien</h2>
                <form action="#" method="post">
                    <!-- Input Nama -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Nama" required>
                    </div>

                    <!-- Input Alamat -->
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat" required>
                    </div>

                    <!-- Input No. KTP -->
                    <div class="mb-3">
                        <label for="noktp" class="form-label">No. KTP</label>
                        <input type="text" class="form-control" id="noktp" name="noktp" placeholder="Masukkan No. KTP" required>
                    </div>

                    <!-- Input No. HP -->
                    <div class="mb-3">
                        <label for="nohp" class="form-label">No. HP</label>
                        <input type="text" class="form-control" id="nohp" name="nohp" placeholder="Masukkan No. HP" required>
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" class="btn btn-primary w-100">Registrasi</button>
                </form>

                <!-- Menampilkan pesan error jika ada -->
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger mt-3" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <!-- Menampilkan pesan sukses jika registrasi berhasil -->
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success mt-3" role="alert">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <!-- Link login bagi yang sudah punya akun -->
                <div class="text-center mt-3">
                    <a href="login_pasien.php" class="text-decoration-none">Sudah punya akun? Login di sini.</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Link ke Bootstrap JS (opsional, jika menggunakan fitur interaktif Bootstrap seperti modal) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
