<?php
include('../koneksi.php');

$id_jadwal = $_POST['id_jadwal'];
$hari = $_POST['hari'];
$jam_mulai = $_POST['jam_mulai'];
$jam_selesai = $_POST['jam_selesai'];
$status = $_POST['status'];
$dokter_nama = $_POST['dokter'];

// Mendapatkan id_dokter berdasarkan nama dokter
$sql = "SELECT id FROM dokter WHERE nama = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $dokter_nama);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $dokter_id = $result->fetch_assoc()['id'];
} else {
    echo "Dokter tidak ditemukan di database.";
    exit;
}

// Jika status diubah menjadi 'aktif', nonaktifkan jadwal lain yang 'aktif' untuk dokter ini
if ($status === 'aktif') {
    $deactivate_sql = "UPDATE jadwal_periksa SET status = 'tidak aktif' WHERE id_dokter = ? AND status = 'aktif'";
    $stmt_deactivate = $conn->prepare($deactivate_sql);
    $stmt_deactivate->bind_param("i", $dokter_id);
    $stmt_deactivate->execute();
    $stmt_deactivate->close();
}

// Memperbarui jadwal dengan data baru
$update_sql = "UPDATE jadwal_periksa 
               SET hari = ?, jam_mulai = ?, jam_selesai = ?, status = ?, id_dokter = ? 
               WHERE id = ?";
$stmt_update = $conn->prepare($update_sql);
$stmt_update->bind_param("ssssii", $hari, $jam_mulai, $jam_selesai, $status, $dokter_id, $id_jadwal);

if ($stmt_update->execute()) {
    echo "<script>
            alert('Jadwal berhasil diperbarui!');
            window.location.href = 'dashboard_dokter.php';
          </script>";
    exit();
} else {
    echo "Terjadi kesalahan saat memperbarui jadwal: " . $stmt_update->error;
}

$stmt_update->close();
$conn->close();
?>
