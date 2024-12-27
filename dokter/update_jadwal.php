<?php
include('../koneksi.php');

$id_jadwal = $_POST['id_jadwal'];
$hari = $_POST['hari'];
$jam_mulai = $_POST['jam_mulai'];
$jam_selesai = $_POST['jam_selesai'];
$dokter_nama = $_POST['dokter'];  


$sql = "SELECT id FROM dokter WHERE nama = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $dokter_nama);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $dokter_id = $result->fetch_assoc()['id'];
    echo "Dokter ID yang ditemukan: " . $dokter_id . "<br>";
} else {
    echo "Dokter tidak ditemukan di database.<br>";
    exit; 
}

$update_sql = "UPDATE jadwal_periksa 
               SET hari = ?, jam_mulai = ?, jam_selesai = ?, id_dokter = ? 
               WHERE id = ?";
$stmt_update = $conn->prepare($update_sql);
$stmt_update->bind_param("ssssi", $hari, $jam_mulai, $jam_selesai, $dokter_id, $id_jadwal);


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
