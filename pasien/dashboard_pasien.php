<?php
session_start();
if (!isset($_SESSION['id_pasien'])) {
    header("Location: login_pasien.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/dashboard_pasien.css">
</head>

<body class="bg-light">
    <div class="sidebar">
        <h3>Dashboard Pasien</h3>
        <a href="#" id="dashboard-tab">Dashboard</a>
        <a href="#" id="riwayat-poli-tab">Riwayat Poli</a>
        <a href="../admin/logout.php" class="text-white">Logout</a>
    </div>

    <div class="main-content">
        <h1>Selamat Datang, Pasien!</h1>
        <div id="dashboard-content" class="content-section">
            <h3>Dashboard</h3>
        </div>
        <div id="riwayat-poli-content" class="content-section">
            <div class="card">
                <div class="card-header">
                    <h5>Riwayat Poli yang Pernah Dikunjungi</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Poli</th>
                                <th>Tanggal Kunjungan</th>
                                <th>Keluhan</th>
                                <th>Diagnosa</th>
                                <th>Pengobatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Poli Umum</td>
                                <td>2024-11-15</td>
                                <td>Demam dan Pusing</td>
                                <td>Infeksi Saluran Pernapasan</td>
                                <td>Paracetamol</td>
                            </tr>
                            <tr>
                                <td>Poli Mata</td>
                                <td>2024-11-10</td>
                                <td>Gangguan Penglihatan</td>
                                <td>Miopi</td>
                                <td>Kacamata</td>
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
            document.getElementById('riwayat-poli-content').style.display = 'none';
        }

        document.getElementById('dashboard-tab').addEventListener('click', function() {
            document.getElementById('dashboard-content').style.display = 'block';
            document.getElementById('riwayat-poli-content').style.display = 'none';
        });

        document.getElementById('riwayat-poli-tab').addEventListener('click', function() {
            document.getElementById('riwayat-poli-content').style.display = 'block';
            document.getElementById('dashboard-content').style.display = 'none';
        });
    </script>
</body>

</html>
