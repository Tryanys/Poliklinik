<?php
session_start();

if (!isset($_SESSION['dokter'])) {
    header("Location: login_dokter.php");
    exit();
}

$dokter_nama = $_SESSION['dokter'];

// Koneksi database
include('../koneksi.php');
if (isset($_POST['tambah_jadwal'])) {
    // Ambil data dari formulir
    $id_dokter = $_POST['id_dokter'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    // Cek apakah jadwal dengan dokter, hari, dan jam yang sama sudah ada
    $cek_sql = "SELECT COUNT(*) FROM jadwal_periksa WHERE id_dokter = ? AND hari = ? AND jam_mulai = ? AND jam_selesai = ?";
    $stmt = $conn->prepare($cek_sql);
    $stmt->bind_param("isss", $id_dokter, $hari, $jam_mulai, $jam_selesai);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "<script>alert('Jadwal dengan dokter, hari, dan jam tersebut sudah ada.');</script>";
    } else {
        // Jika status 'aktif', nonaktifkan semua jadwal lain untuk dokter ini
        if ($status == 'aktif') {
            $deactivate_sql = "UPDATE jadwal_periksa SET status = 'tidak aktif' WHERE id_dokter = ?";
            $stmt = $conn->prepare($deactivate_sql);
            $stmt->bind_param("i", $id_dokter);
            $stmt->execute();
            $stmt->close();
        }

        // Tambahkan jadwal baru
        $insert_sql = "INSERT INTO jadwal_periksa (id_dokter, hari, jam_mulai, jam_selesai, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("issss", $id_dokter, $hari, $jam_mulai, $jam_selesai, $status);
        if ($stmt->execute()) {
            echo "<script>alert('Jadwal berhasil ditambahkan.');</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat menambahkan jadwal.');</script>";
        }
        $stmt->close();
    }
}

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
        <a href="#" id="jadwal-tab">Jadwal Periksa</a>
        <a href="#" id="periksa-pasien-tab">Periksa Pasien</a>
        <a href="#" id="riwayat-tab">Riwayat Pasien</a>
        <a href="#" id="profile-tab">Profile</a>
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
                    <h5>Jadwal Periksa Hari Ini</h5>
                    <button class="btn btn-primary" id="tambah-jadwal-btn" data-bs-toggle="modal" data-bs-target="#tambahJadwalModal">Tambah Jadwal</button>
                </div>
                <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID Jadwal</th>
                            <th>Dokter</th>
                            <th>Hari</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch schedules from the database
                        $sql = "SELECT j.id, j.hari, j.jam_mulai, j.jam_selesai, j.status, d.nama AS nama
                                FROM jadwal_periksa j
                                JOIN dokter d ON j.id_dokter = d.id
                                WHERE d.nama = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $dokter_nama);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['id']) . "</td>
                                    <td>" . htmlspecialchars($row['nama']) . "</td>
                                    <td>" . htmlspecialchars($row['hari']) . "</td>
                                    <td>" . htmlspecialchars($row['jam_mulai']) . "</td>
                                    <td>" . htmlspecialchars($row['jam_selesai']) . "</td>
                                    <td>" . htmlspecialchars($row['status']) . "</td>
                                    <td>
                                        <button class='btn btn-warning edit-btn'
                                                data-bs-toggle='modal'
                                                data-bs-target='#editJadwalModal'
                                                data-id='" . htmlspecialchars($row['id']) . "'
                                                data-dokter-nama='" . htmlspecialchars($row['nama']) . "'
                                                data-hari='" . htmlspecialchars($row['hari']) . "'
                                                data-jam_mulai='" . htmlspecialchars($row['jam_mulai']) . "'
                                                data-jam_selesai='" . htmlspecialchars($row['jam_selesai']) . "'
                                                data-status='" . htmlspecialchars($row['status']) . "'>
                                            Edit
                                        </button>
                                    </td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="periksa-pasien-content" class="content-section">
    <h3>Daftar Periksa Pasien</h3>
    
    <!-- Tabel Daftar Periksa Pasien -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No Antrian</th>
                <th>Nama Pasien</th>
                <th>Keluhan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
            // Koneksi ke database
            include('../koneksi.php'); 
            $dokter_nama = $_SESSION['dokter'];

            // Query untuk mengambil data pasien dengan JOIN antara daftar_poli dan pasien
            $sql = "SELECT dp.no_antrian, p.nama, dp.keluhan, dp.status_periksa 
                    FROM daftar_poli dp 
                    JOIN pasien p ON dp.id_pasien = p.id
                    JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
                    JOIN dokter d ON jp.id_dokter = d.id 
                    WHERE d.nama = ?";

            // Mempersiapkan statement
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Error dalam persiapan statement: " . $conn->error);
            }

            // Binding parameter
            $stmt->bind_param("s", $dokter_nama);

            // Mengeksekusi query
            if ($stmt->execute()) {
                $result = $stmt->get_result();

                // Cek apakah ada data yang ditemukan
                if ($result->num_rows > 0) {
                    // Loop melalui hasil query dan tampilkan dalam tabel
                    while ($row = $result->fetch_assoc()) {
                        $no_antrian = $row['no_antrian'];
                        $nama_pasien = $row['nama'];
                        $keluhan = $row['keluhan'];
                        $status_periksa = $row['status_periksa'];

                        echo "<tr>
                                <td>" . htmlspecialchars($no_antrian) . "</td>
                                <td>" . htmlspecialchars($nama_pasien) . "</td>
                                <td>" . htmlspecialchars($keluhan) . "</td>
                                <td>";

                        // Menampilkan tombol sesuai dengan status pemeriksaan
                        if ($status_periksa == 'belum diperiksa') {
                            echo "<button class='btn btn-info' 
                                    data-bs-toggle='modal' 
                                    data-bs-target='#periksaModal'
                                    onclick='setPasienData(\"" . htmlspecialchars($no_antrian) . "\", \"" . htmlspecialchars($nama_pasien) . "\", \"" . htmlspecialchars($keluhan) . "\")'>Periksa</button>";
                        } else {
                            echo "<button class='btn btn-secondary' 
                                    onclick='showDetail(\"" . htmlspecialchars($no_antrian) . "\")'>Detail</button>";
                        }

                        echo "</td></tr>";
                    }
                } else {
                    // Jika tidak ada data
                    echo "<tr><td colspan='4'>Tidak ada data pasien.</td></tr>";
                }
            } else {
                // Jika query gagal dieksekusi
                echo "<tr><td colspan='4'>Terjadi kesalahan saat mengambil data pasien.</td></tr>";
            }

            // Menutup statement
            $stmt->close();
            ?>
        </tbody>
    </table>
</div>
            <!-- Modal Pemeriksaan Pasien -->
<div class="modal fade" id="periksaModal" tabindex="-1" aria-labelledby="periksaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="periksaModalLabel">Form Pemeriksaan Pasien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form Pemeriksaan -->
                <form action="simpan_pemeriksaan.php" method="POST">
                <input type="hidden" id="no_antrian" name="no_antrian">
                    <div class="mb-3">
                        <label for="nama_pasien" class="form-label">Nama Pasien</label>
                        <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tanggal_periksa" class="form-label">Tanggal Periksa</label>
                        <input type="date" class="form-control" id="tanggal_periksa" name="tanggal_periksa" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="obat" class="form-label">Pilih Obat</label>
                        <select class="form-select" id="obat" name="obat" required onchange="hitungBiaya()">
                            <option value="">Pilih Obat</option>
                            <?php
                            // Koneksi ke database untuk mengambil data obat
                            include('../koneksi.php');
                            
                            $sql = "SELECT id, nama_obat, harga FROM obat";
                            $result = $conn->query($sql);

                            // Menampilkan obat di dropdown
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='".$row['id_obat']."' data-harga='".$row['harga']."'>".$row['nama_obat']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="biaya_periksa" class="form-label">Biaya Periksa</label>
                        <input type="text" class="form-control" id="biaya_periksa" name="biaya_periksa" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Pemeriksaan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include('../koneksi.php'); // Pastikan koneksi.php berisi koneksi ke database

// Query untuk mengambil data pasien
$sql = "SELECT nama, alamat, no_ktp, no_hp, no_rm FROM pasien";
$result = $conn->query($sql);
?>

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
                        <th>Alamat</th>
                        <th>No KTP</th>
                        <th>No HP</th>
                        <th>No RM</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Cek apakah ada data yang ditemukan
                    if ($result->num_rows > 0) {
                        // Loop untuk menampilkan data pasien
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row['nama'] . "</td>
                                    <td>" . $row['alamat'] . "</td>
                                    <td>" . $row['no_ktp'] . "</td>
                                    <td>" . $row['no_hp'] . "</td>
                                    <td>" . $row['no_rm'] . "</td>
                                  </tr>";
                        }
                    } else {
                        // Jika tidak ada data pasien
                        echo "<tr><td colspan='5'>Tidak ada riwayat pasien.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


    <!-- Modal Tambah Jadwal -->
    <div class="modal fade" id="tambahJadwalModal" tabindex="-1" aria-labelledby="tambahJadwalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahJadwalModalLabel">Tambah Jadwal Periksa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="id_dokter" class="form-label">Nama Dokter</label>
                            <select class="form-select" id="id_dokter" name="id_dokter" required>
                                <?php
                                // Ambil data dokter untuk dipilih
                                $dokter_sql = "SELECT * FROM dokter";
                                $dokter_result = $conn->query($dokter_sql);
                                while ($dokter_row = $dokter_result->fetch_assoc()) {
                                    echo "<option value='" . $dokter_row['id'] . "'>" . $dokter_row['nama'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="hari" class="form-label">Hari</label>
                            <input type="text" class="form-control" id="hari" name="hari" required>
                        </div>
                        <div class="mb-3">
                            <label for="jam_mulai" class="form-label">Jam Mulai</label>
                            <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" required>
                        </div>
                        <div class="mb-3">
                            <label for="jam_selesai" class="form-label">Jam Selesai</label>
                            <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" required>
                        </div>


                        <button type="submit" name="tambah_jadwal" class="btn btn-primary">Tambah Jadwal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
   <div id="profile-content" class="content-section">
    <h3>Profile Dokter</h3>
    <div class="card">
        <div class="card-body">
            <form action="update_profile.php" method="POST">
                <?php
                // Koneksi ke database
                include('../koneksi.php');

                // Query untuk mengambil data dokter
                $dokter_sql = "SELECT * FROM dokter WHERE nama = ?";
                $stmt = $conn->prepare($dokter_sql);
                $stmt->bind_param("s", $_SESSION['dokter']);
                $stmt->execute();
                $result = $stmt->get_result();
                $dokter_row = $result->fetch_assoc();

                ?>
                <div class="mb-3">
                    <label for="nama_dokter" class="form-label">Nama Dokter</label>
                    <input type="text" class="form-control" id="nama_dokter" name="nama_dokter" value="<?php echo $dokter_row['nama']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="alamat_dokter" class="form-label">Alamat Dokter</label>
                    <input type="text" class="form-control" id="alamat_dokter" name="alamat_dokter" value="<?php echo $dokter_row['alamat']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="no_hp_dokter" class="form-label">No HP Dokter</label>
                    <input type="text" class="form-control" id="no_hp_dokter" name="no_hp_dokter" value="<?php echo $dokter_row['no_hp']; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editJadwalModal" tabindex="-1" aria-labelledby="editJadwalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editJadwalModalLabel">Edit Jadwal Periksa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editJadwalForm" method="POST" action="update_jadwal.php">
                    <input type="hidden" id="id_jadwal" name="id_jadwal">
                    
                    <div class="mb-3">
                        <label for="dokter" class="form-label">Dokter</label>
                        <input type="text" class="form-control" id="dokter" name="dokter" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="hari" class="form-label">Hari</label>
                        <input type="text" class="form-control" id="hari" name="hari" required>
                    </div>

                    <div class="mb-3">
                        <label for="jam_mulai" class="form-label">Jam Mulai</label>
                        <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" required>
                    </div>

                    <div class="mb-3">
                        <label for="jam_selesai" class="form-label">Jam Selesai</label>
                        <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="aktif">Aktif</option>
                            <option value="tidak aktif">Tidak Aktif</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script>
    function setPasienData(no_antrian, nama_pasien, keluhan) {
        document.getElementById('nama_pasien').value = nama_pasien;
        document.getElementById('no_antrian').value = no_antrian; 
    }
    function showDetail(no_antrian) {
        alert("Menampilkan detail pemeriksaan untuk No Antrian: " + no_antrian);
    }

    function hitungBiaya() {
        var obatSelect = document.getElementById('obat');
        var selectedOption = obatSelect.options[obatSelect.selectedIndex];
        var hargaObat = selectedOption.getAttribute('data-harga');
        var biayaJasaDokter = 150000;
        var totalBiaya = parseInt(hargaObat) + biayaJasaDokter;

        document.getElementById('biaya_periksa').value = totalBiaya;
    }
</script>

<script>
    $('#editJadwalModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Tombol yang memicu modal
        var idJadwal = button.data('id');
        var dokterId = button.data('dokter');  // Cek apakah ini dikirimkan
        var dokterNama = button.data('dokter-nama'); // Nama dokter
        var hari = button.data('hari');
        var jamMulai = button.data('jam_mulai');
        var jamSelesai = button.data('jam_selesai');

        // Debugging: Cek apakah nilai yang diterima benar
        console.log('ID Jadwal:', idJadwal);
        console.log('Dokter ID:', dokterId);  // Periksa apakah ini bernilai benar
        console.log('Dokter Nama:', dokterNama);
        console.log('Hari:', hari);
        console.log('Jam Mulai:', jamMulai);
        console.log('Jam Selesai:', jamSelesai);

        // Isi nilai ke dalam form modal
        var modal = $(this);
        modal.find('#id_jadwal').val(idJadwal);
        modal.find('#dokter').val(dokterNama);  // Tampilkan nama dokter
        modal.find('#hari').val(hari);
        modal.find('#jam_mulai').val(jamMulai);
        modal.find('#jam_selesai').val(jamSelesai);
    });
</script>



    <script>
    // Pastikan format waktu selalu 24 jam
    document.getElementById('jam_mulai').addEventListener('change', function () {
        let time = this.value;
        if (time) {
            // Format jam untuk memastikan selalu dalam format 24 jam
            let timeArray = time.split(':');
            let hours = timeArray[0].padStart(2, '0');
            let minutes = timeArray[1].padStart(2, '0');
            this.value = hours + ':' + minutes;
        }
    });

    document.getElementById('jam_selesai').addEventListener('change', function () {
        let time = this.value;
        if (time) {
            // Format jam untuk memastikan selalu dalam format 24 jam
            let timeArray = time.split(':');
            let hours = timeArray[0].padStart(2, '0');
            let minutes = timeArray[1].padStart(2, '0');
            this.value = hours + ':' + minutes;
        }
    });
</script>

    <script>
        window.onload = function() {
            document.getElementById('dashboard-content').style.display = 'block';
            document.getElementById('jadwal-content').style.display = 'none';
            document.getElementById('periksa-pasien-content').style.display = 'none';
            document.getElementById('riwayat-content').style.display = 'none';
            document.getElementById('profile-content').style.display = 'none';
        }

        document.getElementById('dashboard-tab').addEventListener('click', function() {
            document.getElementById('dashboard-content').style.display = 'block';
            document.getElementById('jadwal-content').style.display = 'none';
            document.getElementById('periksa-pasien-content').style.display = 'none';
            document.getElementById('riwayat-content').style.display = 'none';
            document.getElementById('profile-content').style.display = 'none';
        });

        document.getElementById('jadwal-tab').addEventListener('click', function() {
            document.getElementById('jadwal-content').style.display = 'block';
            document.getElementById('periksa-pasien-content').style.display = 'none';
            document.getElementById('dashboard-content').style.display = 'none';
            document.getElementById('riwayat-content').style.display = 'none';
            document.getElementById('profile-content').style.display = 'none';
        });
        document.getElementById('periksa-pasien-tab').addEventListener('click', function() {
            document.getElementById('periksa-pasien-content').style.display = 'block';
            document.getElementById('dashboard-content').style.display = 'none';
            document.getElementById('jadwal-content').style.display = 'none';
            document.getElementById('riwayat-content').style.display = 'none';
            document.getElementById('profile-content').style.display = 'none';
        
    });

        document.getElementById('riwayat-tab').addEventListener('click', function() {
            document.getElementById('riwayat-content').style.display = 'block';
            document.getElementById('dashboard-content').style.display = 'none';
            document.getElementById('jadwal-content').style.display = 'none';
            document.getElementById('periksa-pasien-content').style.display = 'none';
            document.getElementById('profile-content').style.display = 'none';
        });
        document.getElementById('profile-tab').addEventListener('click', function() {
            document.getElementById('profile-content').style.display = 'block';
            document.getElementById('dashboard-content').style.display = 'none';
            document.getElementById('jadwal-content').style.display = 'none';
            document.getElementById('periksa-pasien-content').style.display = 'none';
            document.getElementById('riwayat-content').style.display = 'none';
});

    </script>
</body>

</html>
