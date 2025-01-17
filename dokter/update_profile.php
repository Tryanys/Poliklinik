<?php
// Koneksi ke database
include('../koneksi.php');
session_start();
// Query untuk mengupdate data dokter
$sql = "UPDATE dokter SET nama = ?, alamat = ?, no_hp = ? WHERE nama = ?";

// Mempersiapkan statement
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error dalam persiapan statement: " . $conn->error);
}

// Binding parameter
$nama_dokter = $_POST['nama_dokter'];
$alamat_dokter = $_POST['alamat_dokter'];
$no_hp_dokter = $_POST['no_hp_dokter'];
$nama_dokter_lama = isset($_SESSION['dokter']) ? $_SESSION['dokter'] : '';

$stmt->bind_param("ssss", $nama_dokter, $alamat_dokter, $no_hp_dokter, $nama_dokter_lama);

// Mengeksekusi query
if ($stmt->execute()) {
    // Update session dengan nama dokter yang baru
    $_SESSION['dokter'] = $nama_dokter;
    echo "<script>alert('Profile berhasil diupdate!');</script>";
    echo "<script>window.location.href='dashboard_dokter.php';</script>";
} else {
    echo "<script>alert('Terjadi kesalahan saat mengupdate profile!');</script>";
    echo "<script>window.location.href='dashboard_dokter.php';</script>";
}

$stmt->close();
?>
 