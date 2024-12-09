<?php
include('../koneksi.php'); // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id = intval($_POST['id']);
    $entityType = $_POST['type']; // Jenis entitas (dokter, pasien, poli, obat)

    // Tentukan query update sesuai dengan jenis entitas
    if ($entityType == 'dokter') {
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $no_hp = $_POST['no_hp'];
        $id_poli = intval($_POST['id_poli']);
        
        // Query untuk update data dokter
        $sql = "UPDATE dokter SET nama = ?, alamat = ?, no_hp = ?, id_poli = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssii", $nama, $alamat, $no_hp, $id_poli, $id);
    } elseif ($entityType == 'pasien') {
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $no_ktp = $_POST['no_ktp'];
        $no_hp = $_POST['no_hp'];

        // Query untuk update data pasien
        $sql = "UPDATE pasien SET nama = ?, alamat = ?, no_ktp = ? , no_hp = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nama, $alamat, $no_ktp , $no_hp, $id);
    } elseif ($entityType == 'poli') {
        $nama_poli = $_POST['nama_poli'];
        $keterangan = $_POST['keterangan'];
        // Query untuk update data poli
        $sql = "UPDATE poli SET nama_poli = ?, keterangan = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nama_poli, $keterangan ,$id);
    } elseif ($entityType == 'obat') {
        $nama_obat = $_POST['nama_obat'];
        $harga = $_POST['harga'];
        $kemasan = $_POST['kemasan'];
        // Query untuk update data obat
        $sql = "UPDATE obat SET nama_obat = ?, kemasan = ? , harga = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $nama_obat, $kemasan , $harga, $id);
    }

    // Eksekusi query update
    if ($stmt->execute()) {
        echo "<script>
            alert('Data berhasil diperbarui.');
            window.location.href = 'dashboard_admin.php';
        </script>";
    } else {
        echo "<script>
            alert('Terjadi kesalahan saat memperbarui data.');
            window.history.back();
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
