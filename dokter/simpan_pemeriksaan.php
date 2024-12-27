<?php
include('../koneksi.php'); // Pastikan koneksi.php berisi koneksi ke database

// Memeriksa apakah data yang diperlukan ada
if (isset($_POST['no_antrian'], $_POST['tanggal_periksa'], $_POST['catatan'], $_POST['obat'])) {
    $no_antrian = $_POST['no_antrian'];
    $tanggal_periksa = $_POST['tanggal_periksa'];
    $catatan = $_POST['catatan'];
    $obat_id = $_POST['obat'];

    // Query untuk mendapatkan harga obat
    $sql = "SELECT harga FROM obat WHERE id = ?";
    $stmt = $conn->prepare($sql);

    // Cek apakah query berhasil dipersiapkan
    if ($stmt === false) {
        die('Error preparing the query: ' . $conn->error);
    }

    $stmt->bind_param("i", $obat_id);
    $stmt->execute();
    $stmt->bind_result($harga_obat);
    $stmt->fetch();
    $stmt->close();

    // Biaya pemeriksaan (contoh bisa diganti sesuai dengan logika bisnis)
    $biaya_periksa = 150000 + $harga_obat; // Biaya pemeriksaan dasar + harga obat

    // Query untuk menyimpan pemeriksaan ke tabel periksa
    $sql_insert = "INSERT INTO periksa (id_daftar_poli, tgl_periksa, catatan, biaya_periksa) 
                   SELECT id, ?, ?, ? FROM daftar_poli WHERE no_antrian = ?";
    $stmt_insert = $conn->prepare($sql_insert);

    // Cek jika query insert berhasil dipersiapkan
    if ($stmt_insert === false) {
        die('Error preparing the insert query: ' . $conn->error);
    }

    $stmt_insert->bind_param("ssds", $tanggal_periksa, $catatan, $biaya_periksa, $no_antrian);
    $stmt_insert->execute();
    $stmt_insert->close();

    // Update status pemeriksaan di tabel daftar_poli
    $sql_update = "UPDATE daftar_poli SET status_periksa = 'sudah diperiksa' WHERE no_antrian = ?";
    $stmt_update = $conn->prepare($sql_update);

    // Cek jika query update berhasil dipersiapkan
    if ($stmt_update === false) {
        die('Error preparing the update query: ' . $conn->error);
    }

    $stmt_update->bind_param("s", $no_antrian);
    $stmt_update->execute();
    $stmt_update->close();

    // Redirect ke halaman daftar pemeriksaan setelah sukses
    header("Location: dashboard_dokter.php");
    exit;
} else {
    echo "No antrian tidak valid.";
}
?>
