<?php
include('../koneksi.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama_obat'];
    $ket = $_POST['kategori_obat'];
    $harga = $_POST['harga_obat'];

    $sql = "INSERT INTO obat (nama_obat, kemasan, harga) 
            VALUES ('$nama', '$ket', '$harga')";

    if ($conn->query($sql) === TRUE) {
        echo "Data poli berhasil ditambahkan!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
