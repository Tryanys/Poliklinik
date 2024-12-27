<?php
include('../koneksi.php');

// Fungsi untuk generate no_rm
function generateNoRM($conn) {
    // Mendapatkan tahun dan bulan sekarang
    $year_month = date("Ym");

    // Query untuk menghitung jumlah pasien yang ada
    $sql = "SELECT COUNT(id) AS count FROM pasien";
    $result = $conn->query($sql);

    // Menentukan urutan no_rm berdasarkan jumlah pasien
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $count = $row['count'] + 1; // Menambahkan 1 untuk ID yang baru
        $sequence = sprintf("%03d", $count); // Format no_rm sesuai dengan 3 digit
    } else {
        // Jika belum ada pasien, urutan pertama adalah 001
        $sequence = "001";
    }

    // Menghasilkan no_rm dengan format YYYYMM-XXX
    return $year_month . "-" . $sequence;
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Jika metode GET, hanya generate No RM
    echo generateNoRM($conn);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Jika metode POST, tambahkan data pasien ke database
    $nama_pasien = $_POST['nama_pasien'];
    $alamat_pasien = $_POST['alamat_pasien'];
    $no_ktp = $_POST['no_ktp'];
    $no_hp_pasien = $_POST['no_hp_pasien'];
    $no_rm = $_POST['no_rm']; // No RM dari input

    $timestamp = time();
    $sql = "INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm) 
            VALUES ('$nama_pasien', '$alamat_pasien', '$no_ktp', '$no_hp_pasien', '$no_rm')";

    if ($conn->query($sql) === TRUE) {
        echo "Data pasien berhasil ditambahkan dengan No RM: $no_rm";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
