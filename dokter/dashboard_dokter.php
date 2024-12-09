<?php
session_start();

if (!isset($_SESSION['dokter'])) {
    header("Location: login_dokter.php");
    exit();
}

$dokter_nama = $_SESSION['dokter'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dokter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/dashboard_dokter.css">
</head>

<body class="bg-light">
    <div class="sidebar">
        <h3>Dashboard Dokter</h3>
        <a href="#" id="dashboard-tab">Dashboard</a>
        <a href="#" id="jadwal-tab">Jadwal Dokter</a>
        <a href="#" id="riwayat-tab">Riwayat Pasien</a>
        <a href="../admin/logout.php" class="text-white">Logout</a>
    </div>

    <div class="main-content">
        <h1>Selamat Datang, Dokter!</h1>

        <div id="dashboard-content" class="content-section">
            <h3>Dashboard</h3>
        </div>

        <div id="jadwal-content" class="content-section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Jadwal Dokter Hari Ini</h5>
                    <button class="btn btn-primary" id="tambah-jadwal-btn">Tambah Jadwal</button>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Dokter</th>
                                <th>Hari</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Dr. Andi</td>
                                <td>Senin</td>
                                <td>09:00 AM</td>
                                <td>12:00 PM</td>
                                <td><span class="badge bg-success">Tersedia</span></td>
                                <td><button class="btn btn-warning btn-sm">Edit</button></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Dr. Siti</td>
                                <td>Selasa</td>
                                <td>02:00 PM</td>
                                <td>05:00 PM</td>
                                <td><span class="badge bg-warning">Tunggu konfirmasi</span></td>
                                <td><button class="btn btn-warning btn-sm">Edit</button></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Dr. Budi</td>
                                <td>Rabu</td>
                                <td>08:00 AM</td>
                                <td>11:00 AM</td>
                                <td><span class="badge bg-danger">Tidak Tersedia</span></td>
                                <td><button class="btn btn-warning btn-sm">Edit</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="riwayat-content" class="content-section">
            <div class="card">
                <div class="card-header">
                    <h5>Riwayat Pasien yang Pernah Dikunjungi</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Pasien</th>
                                <th>Keluhan</th>
                                <th>Diagnosa</th>
                                <th>Pengobatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>John Doe</td>
                                <td>Demam tinggi</td>
                                <td>Infeksi Saluran Pernapasan</td>
                                <td>Paracetamol</td>
                            </tr>
                            <tr>
                                <td>Jane Smith</td>
                                <td>Pusing dan mual</td>
                                <td>Vertigo</td>
                                <td>Betahistine</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        window.onload = function() {
            document.getElementById('dashboard-content').style.display = 'block';
            document.getElementById('jadwal-content').style.display = 'none';
            document.getElementById('riwayat-content').style.display = 'none';
        }

        document.getElementById('dashboard-tab').addEventListener('click', function() {
            document.getElementById('dashboard-content').style.display = 'block';
            document.getElementById('jadwal-content').style.display = 'none';
            document.getElementById('riwayat-content').style.display = 'none';
        });

        document.getElementById('jadwal-tab').addEventListener('click', function() {
            document.getElementById('jadwal-content').style.display = 'block';
            document.getElementById('dashboard-content').style.display = 'none';
            document.getElementById('riwayat-content').style.display = 'none';
        });

        document.getElementById('riwayat-tab').addEventListener('click', function() {
            document.getElementById('riwayat-content').style.display = 'block';
            document.getElementById('jadwal-content').style.display = 'none';
            document.getElementById('dashboard-content').style.display = 'none';
        });

        // Event listener untuk tombol "Tambah Jadwal"
        document.getElementById('tambah-jadwal-btn').addEventListener('click', function() {
            alert('Form untuk menambahkan jadwal dokter akan muncul!');
            // Anda dapat menambahkan logika untuk menampilkan form atau modals di sini
        });
    </script>
</body>

</html>
