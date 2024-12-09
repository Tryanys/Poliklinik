<?php
include('../koneksi.php');

if (isset($_POST['id'])) {
    $pasienId = intval($_POST['id']); 

    // Query untuk menghapus data dokter berdasarkan ID
    $sql = "DELETE FROM pasien WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pasienId); // Bind parameter dengan tipe integer

    // Eksekusi query   
    if ($stmt->execute()) {
        echo "Data dokter berhasil dihapus.";
    } else {
        echo "Terjadi kesalahan saat menghapus data dokter.";
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();
} else {
    echo "ID pasien tidak ditemukan.";
}
?>
