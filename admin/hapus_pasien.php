<?php
include('../koneksi.php');

if (isset($_POST['id'])) {
    $pasienId = intval($_POST['id']);

    // Hapus data berdasarkan ID
    $sql = "DELETE FROM pasien WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pasienId);

    if ($stmt->execute()) {
        // Urutkan ulang ID
        $reorderSql = "SET @count = 0; UPDATE pasien SET id = @count := @count + 1 ORDER BY id";
        if ($conn->query($reorderSql)) {
            // Reset auto_increment
            $resetAutoIncrementSql = "ALTER TABLE pasien AUTO_INCREMENT = (SELECT MAX(id) + 1 FROM pasien)";
            if ($conn->query($resetAutoIncrementSql)) {
                echo "Data pasien berhasil dihapus dan ID diurutkan ulang.";
            } else {
                echo "Data pasien berhasil dihapus, tetapi gagal mereset auto_increment.";
            }
        } else {
            echo "Data pasien berhasil dihapus, tetapi gagal mengurutkan ulang ID.";
        }
    } else {
        echo "Terjadi kesalahan saat menghapus data pasien.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ID pasien tidak ditemukan.";
}
?>
