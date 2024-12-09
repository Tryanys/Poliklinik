<?php
include('../koneksi.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pasien = $_POST['nama_pasien'];
    $alamat_pasien = $_POST['alamat_pasien'];
    $no_ktp = $_POST['no_ktp'];
    $no_hp_pasien = $_POST['no_hp_pasien'];
    $no_rm = $_POST['no_rm'];

    $sql = "INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm) 
            VALUES ('$nama_pasien', '$alamat_pasien', '$no_ktp', '$no_hp_pasien', '$no_rm')";

    if ($conn->query($sql) === TRUE) {
        echo "Data pasien berhasil ditambahkan!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
