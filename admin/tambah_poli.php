<?php
include('../koneksi.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_poli = $_POST['nama_poli'];
    $ket_poli = $_POST['keterangan_poli'];

    $sql = "INSERT INTO poli (nama_poli, keterangan) 
            VALUES ('$nama_poli', '$ket_poli')";

    if ($conn->query($sql) === TRUE) {
        echo "Data poli berhasil ditambahkan!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
