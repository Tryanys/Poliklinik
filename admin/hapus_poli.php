<?php
include('../koneksi.php');
if (isset($_POST['id'])) {
    $poliId = intval($_POST['id']); 

    $sql = "DELETE FROM poli WHERE id = ?";
    $stmt = $conn->prepare($sql); 

    $stmt->bind_param("i", $poliId); 

    if ($stmt->execute()) {
        echo "Data poli berhasil dihapus."; 
    } else {
        echo "Terjadi kesalahan saat menghapus data poli."; 
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ID poli tidak ditemukan."; 
}
?>
