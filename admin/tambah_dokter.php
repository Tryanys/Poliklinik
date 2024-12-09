<?php
include('../koneksi.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_dokter = $_POST['nama_dokter'];
    $alamat_dokter = $_POST['alamat_dokter'];
    $no_hp_dokter = $_POST['no_hp_dokter'];
    $id_poli = $_POST['id_poli'];

    $sql = "INSERT INTO dokter (nama, alamat, no_hp, id_poli) 
            VALUES ('$nama_dokter', '$alamat_dokter', '$no_hp_dokter', '$id_poli')";

    if ($conn->query($sql) === TRUE) {
        echo "Data dokter berhasil ditambahkan!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
