<?php
include('../koneksi.php'); // Menyertakan koneksi ke database

if (isset($_POST['id'])) {
    $obatId = intval($_POST['id']); // Mengambil ID obat yang akan dihapus

    // Query untuk menghapus data obat berdasarkan id
    $sql = "DELETE FROM obat WHERE id = ?";
    $stmt = $conn->prepare($sql); // Menyiapkan query

    $stmt->bind_param("i", $obatId); // Mengikat parameter ID

    if ($stmt->execute()) {
        echo "Data obat berhasil dihapus."; // Tanggapan jika berhasil
    } else {
        echo "Terjadi kesalahan saat menghapus data obat."; // Tanggapan jika gagal
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ID obat tidak ditemukan."; // Jika ID tidak ditemukan
}
?>
