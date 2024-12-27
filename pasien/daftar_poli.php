<?php
// Koneksi ke database
include '../koneksi.php';
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_pasien'])) {
    echo "Anda harus login terlebih dahulu.";
    exit;
}

// Ambil id_pasien dari session
$id_pasien = $_SESSION['id_pasien'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id_jadwal = $_POST['id_jadwal'];
    $keluhan = $_POST['keluhan'];

    // Ambil nomor antrian terakhir untuk jadwal yang sama
    $sql_antrian = "SELECT MAX(no_antrian) AS max_antrian FROM daftar_poli WHERE id_jadwal = ?";
    $stmt_antrian = $conn->prepare($sql_antrian);
    $stmt_antrian->bind_param("i", $id_jadwal);
    $stmt_antrian->execute();
    $result_antrian = $stmt_antrian->get_result();
    $row = $result_antrian->fetch_assoc();
    $no_antrian = $row['max_antrian'] ? $row['max_antrian'] + 1 : 1;

    // Masukkan data ke tabel daftar_poli
    $sql_insert = "INSERT INTO daftar_poli (id_pasien, id_jadwal, keluhan, no_antrian) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iisi", $id_pasien, $id_jadwal, $keluhan, $no_antrian);
    if ($stmt_insert->execute()) {
        // Setelah berhasil, arahkan kembali ke dashboard_pasien.php
        header("Location: dashboard_pasien.php?antrian=$no_antrian");
        exit;
    } else {
        echo "Pendaftaran gagal: " . $stmt_insert->error;
    }
}
?>