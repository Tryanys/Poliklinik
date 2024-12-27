<?php
session_start();
if (!isset($_SESSION['id_pasien'])) {
    header("Location: login_pasien.php");
    exit;
}

// Koneksi database
include('../koneksi.php');

// Ambil data poli
$sql_poli = "SELECT * FROM poli";
$result_poli = $conn->query($sql_poli);

// Cek jika poli dipilih
$jadwal_options = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['poli'])) {
    $id_poli = $_POST['poli'];

    $sql_jadwal = "SELECT j.id, j.hari, j.jam_mulai, j.jam_selesai, d.nama
                   FROM jadwal_periksa j
                   JOIN dokter d ON j.id_dokter = d.id
                   WHERE d.id_poli = ?";
    $stmt = $conn->prepare($sql_jadwal);
    $stmt->bind_param("i", $id_poli);
    $stmt->execute();
    $result_jadwal = $stmt->get_result();

    $jadwal_options = [];
    if ($result_jadwal->num_rows > 0) {
        while ($row = $result_jadwal->fetch_assoc()) {
            $jadwal_options[] = $row;
        }
    }
    
    // Return as JSON
    echo json_encode($jadwal_options);
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">
    <div class="sidebar">
        <h3>Dashboard Pasien</h3>
        <a href="#" id="dashboard-tab">Dashboard</a>
        <a href="#" id="riwayat-poli-tab">Poli</a>
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
                    <div class="row">
                        <!-- Form Daftar Poli -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Daftar Poli</h5>
                                </div>
                                <div class="card-body">
                                <form action="daftar_poli.php" method="POST">
                                        <!-- Dropdown Poli -->
                                        <div class="mb-3">
                                            <label for="poli" class="form-label">Pilih Poli</label>
                                            <select class="form-select" id="poli" name="poli" onchange="toggleJadwal()" required>
                                                <option value="">-- Pilih Poli --</option>
                                                <?php while ($row_poli = $result_poli->fetch_assoc()) { ?>
                                                    <option value="<?= $row_poli['id']; ?>" <?= isset($_POST['poli']) && $_POST['poli'] == $row_poli['id'] ? 'selected' : ''; ?>>
                                                        <?= $row_poli['nama_poli']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <!-- Dropdown Jadwal (Hanya Muncul Jika Poli Dipilih) -->
                                        <div id="jadwal-container" style="display: none;">
                                            <div class="mb-3">
                                                <label for="jadwal" class="form-label">Pilih Jadwal</label>
                                                <select class="form-select" id="jadwal" name="id_jadwal" required>
                                                    <option value="">-- Pilih Jadwal --</option>
                                                    <?php foreach ($jadwal_options as $jadwal) { ?>
                                                        <option value="<?= $jadwal['id']; ?>">
                                                            <?= $jadwal['hari'] . ' - ' . $jadwal['jam_mulai'] . ' s/d ' . $jadwal['jam_selesai'] . ' - ' . $jadwal['nama']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Input Keluhan -->
                                        <div class="mb-3">
                                            <label for="keluhan" class="form-label">Keluhan</label>
                                            <textarea class="form-control" id="keluhan" name="keluhan" required></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Daftar Poli</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Nama Poli</th>
                <th>Dokter</th>
                <th>Hari</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>No Antrian</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Koneksi ke database
            include '../koneksi.php';

            // Pastikan session id_pasien ada
            if (!isset($_SESSION['id_pasien'])) {
                echo "<tr><td colspan='8'>Silakan login untuk melihat data.</td></tr>";
                exit;
            }

            $id_pasien = $_SESSION['id_pasien'];
            $sql = "SELECT dp.id_pasien, 
                        p.nama_poli AS poli, 
                        d.nama AS dokter, 
                        jp.hari, 
                        jp.jam_mulai, 
                        jp.jam_selesai, 
                        dp.no_antrian, 
                        dp.id AS id_daftar_poli
                    FROM daftar_poli dp
                    JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
                    JOIN dokter d ON jp.id_dokter = d.id
                    JOIN poli p ON d.id_poli = p.id
                    WHERE dp.id_pasien = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_pasien);
            $stmt->execute();
            $result = $stmt->get_result();

            // Tampilkan data dalam tabel
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Mengecek apakah pasien sudah diperiksa atau belum
                    $id_daftar_poli = $row['id_daftar_poli'];
                    $sqlPeriksa = "SELECT * FROM periksa WHERE id_daftar_poli = ?";
                    $stmtPeriksa = $conn->prepare($sqlPeriksa);
                    $stmtPeriksa->bind_param("i", $id_daftar_poli);
                    $stmtPeriksa->execute();
                    $resultPeriksa = $stmtPeriksa->get_result();
                    $status = "Belum Diperiksa";

                    // Jika sudah diperiksa, ubah status dan tombol aksi
                    if ($resultPeriksa->num_rows > 0) {
                        $status = "Sudah Diperiksa";
                    }

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['poli']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['dokter']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['hari']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['jam_mulai']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['jam_selesai']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['no_antrian']) . "</td>";
                    echo "<td>" . htmlspecialchars($status) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>Tidak ada data jadwal.</td></tr>";
            }

            $stmt->close();
            $conn->close();
            ?>
        </tbody>
    </table>
</div>




                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
function periksaPasien(idDaftarPoli) {
    // Redirect atau buka modal untuk pemeriksaan
    window.location.href = 'periksa_pasien.php?id=' + idDaftarPoli;
}

function lihatDetail(idDaftarPoli) {
    // Redirect ke halaman detail pemeriksaan
    window.location.href = 'detail_periksa.php?id=' + idDaftarPoli;
}
</script>


    <script>
    $(document).ready(function() {
    $('#poli').change(function() {
        var poliId = $(this).val();
        if (poliId != "") {
            $.ajax({
                type: 'POST',
                url: '', // URL untuk memanggil PHP yang menangani logika
                data: { poli: poliId },
                success: function(response) {
                    var data = JSON.parse(response);
                    var jadwalSelect = $('#jadwal');
                    jadwalSelect.empty();
                    
                    if (data.length > 0) {
                        // Menambahkan opsi baru untuk dropdown jadwal
                        jadwalSelect.append('<option value="">-- Pilih Jadwal --</option>');
                        data.forEach(function(jadwal) {
                            jadwalSelect.append('<option value="' + jadwal.id + '">' + jadwal.hari + ' - ' + jadwal.jam_mulai + ' s/d ' + jadwal.jam_selesai + ' - ' + jadwal.nama + '</option>');
                        });
                        $('#jadwal-container').show(); // Menampilkan dropdown jadwal
                    } else {
                        jadwalSelect.append('<option value="">Tidak ada jadwal untuk poli ini</option>');
                        $('#jadwal-container').show(); // Menampilkan pesan jika tidak ada jadwal
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan. Coba lagi.');
                }
            });
        } else {
            $('#jadwal-container').hide(); // Menyembunyikan dropdown jadwal jika poli belum dipilih
        }
    });
});

</script>


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
