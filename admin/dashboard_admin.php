<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dokter</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/dashboard_admin.css">
</head>

<body class="bg-light">
    <div class="d-flex">
        <div class="sidebar p-3 bg-dark text-white" style="width: 250px;">
            <h3>Dashboard Admin</h3>
            <a href="#" id="dashboard-tab" class="text-white">Dashboard</a>
            <a href="#" id="dokter-tab" class="text-white">Dokter</a>
            <a href="#" id="pasien-tab" class="text-white">Pasien</a>
            <a href="#" id="poli-tab" class="text-white">Poli</a>
            <a href="#" id="obat-tab" class="text-white">Obat</a>
            <a href="logout.php" class="text-white">Logout</a>
        </div>
        <div class="main-content p-3 flex-grow-1">
            <h1>Selamat Datang, Admin!</h1>

            <div id="dashboard-content" class="content-section">
                <h3>Dashboard</h3>
            </div>

            <div id="dokter-content" class="content-section" style="display: none;">
                <button class="btn btn-success" id="tambah-dokter-btn">Tambah Data Dokter</button>
                <div class="card mt-3">
                    <div class="card-header">
                        <h5>Daftar Dokter</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Dokter</th>
                                    <th>Alamat</th>
                                    <th>No HP</th>
                                    <th>Poli</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                include('../koneksi.php');

                                // Query untuk mengambil data dokter dan nama poli
                                $sql = "SELECT dokter.id, dokter.nama, dokter.alamat, dokter.no_hp, poli.nama_poli 
                                        FROM dokter
                                        INNER JOIN poli ON dokter.id_poli = poli.id";
                                $result = $conn->query($sql);

                                // Mengecek apakah ada data
                                if ($result->num_rows > 0) {
                                    // Menampilkan data dokter dalam tabel
                                    $no = 1;
                                    while($row = $result->fetch_assoc()) {
                                        echo "<tr id='dokter" . $row["id"] . "'>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row["nama"] . "</td>";
                                        echo "<td>" . $row["alamat"] . "</td>";
                                        echo "<td>" . $row["no_hp"] . "</td>";
                                        echo "<td>" . $row["nama_poli"] . "</td>";
                                        echo "<td>
                                                <a href='edit.php?id=" . $row['id'] . "&type=dokter' class='btn btn-warning btn-sm'>Edit</a>


                                                <button class='btn btn-danger btn-sm btnHapusDokter' data-id='" . $row["id"] . "'>Hapus</button>
                                            </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>Data tidak ditemukan</td></tr>";
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <?php
            // Menutup koneksi
            $conn->close();
            ?>

            <div id="pasien-content" class="content-section" style="display: none;">
                <button class="btn btn-success" id="tambah-pasien-btn">Tambah Data Pasien</button>
                <div class="card mt-3">
                    <div class="card-header">
                        <h5>Daftar Pasien</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pasien</th>
                                    <th>Alamat</th>
                                    <th>No KTP</th>
                                    <th>No HP</th>
                                    <th>No RM</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include('../koneksi.php');
                                $sql = "SELECT id, nama, alamat, no_ktp, no_hp, no_rm FROM pasien";
                                $result = $conn->query($sql);

                                // Mengecek apakah ada data
                                if ($result->num_rows > 0) {
                                    // Menampilkan data pasien dalam tabel
                                    $no = 1;
                                    while($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row["nama"] . "</td>";
                                        echo "<td>" . $row["alamat"] . "</td>";
                                        echo "<td>" . $row["no_ktp"] . "</td>";
                                        echo "<td>" . $row["no_hp"] . "</td>";
                                        echo "<td>" . $row["no_rm"] . "</td>";
                                        echo "<td>
                                                <a href='edit.php?id=" . $row['id'] . "&type=pasien' class='btn btn-warning btn-sm'>Edit</a>
                                                <button class='btn btn-danger btnHapusPasien' data-id='" . $row["id"] . "'>Hapus</button>
                                            </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>Data tidak ditemukan</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php
            // Menutup koneksi
            $conn->close();
            ?>


            <div id="poli-content" class="content-section" style="display: none;">
                <button class="btn btn-success" id="tambah-poli-btn">Tambah Data Poli</button>
                <div class="card mt-3">
                    <div class="card-header">
                        <h5>Daftar Poli</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Poli</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include('../koneksi.php');
                                // Query untuk mengambil data poli
                                $sql = "SELECT id, nama_poli, keterangan FROM poli";
                                $result = $conn->query($sql);

                                // Mengecek apakah ada data
                                if ($result->num_rows > 0) {
                                    // Menampilkan data poli dalam tabel
                                    $no = 1;
                                    while($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row["nama_poli"] . "</td>";
                                        echo "<td>" . $row["keterangan"] . "</td>";
                                        echo "<td>
                                                <a href='edit.php?id=" . $row['id'] . "&type=poli' class='btn btn-warning btn-sm'>Edit</a>
                                                <button class='btn btn-danger btnHapusPoli' data-id='" . $row["id"] . "'>Hapus</button>
                                            </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>Data tidak ditemukan</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php
            // Menutup koneksi
            $conn->close();
            ?>


            <div id="obat-content" class="content-section" style="display: none;">
                <button class="btn btn-success" id="tambah-obat-btn">Tambah Data Obat</button>
                <div class="card mt-3">
                    <div class="card-header">
                        <h5>Daftar Obat</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Obat</th>
                                    <th>Kemasan</th>
                                    <th>Harga</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include('../koneksi.php');
                                // Query untuk mengambil data obat
                                $sql = "SELECT id, nama_obat, kemasan, harga FROM obat";
                                $result = $conn->query($sql);

                                // Mengecek apakah ada data
                                if ($result->num_rows > 0) {
                                    // Menampilkan data obat dalam tabel
                                    $no = 1;
                                    while($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row["nama_obat"] . "</td>";
                                        echo "<td>" . $row["kemasan"] . "</td>";
                                        echo "<td>" . "Rp " . number_format($row["harga"], 0, ',', '.') . "</td>";
                                        echo "<td>
                                                <a href='edit.php?id=" . $row['id'] . "&type=obat' class='btn btn-warning btn-sm'>Edit</a>
                                                <button class='btn btn-danger btnHapusObat' data-id='" . $row["id"] . "'>Hapus</button>
                                            </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5'>Data tidak ditemukan</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php
            // Menutup koneksi
            $conn->close();
            ?>

    </div>
    </div>
    <!-- Modal for Tambah Dokter -->
    <div class="modal fade" id="tambahDokterModal" tabindex="-1" aria-labelledby="tambahDokterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahDokterModalLabel">Tambah Data Dokter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formTambahDokter">
                        <div class="mb-3">
                            <label for="dokterNama" class="form-label">Nama Dokter</label>
                            <input type="text" class="form-control" id="dokterNama" required>
                        </div>
                        <div class="mb-3">
                            <label for="dokterAlamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="dokterAlamat" required>
                        </div>
                        <div class="mb-3">
                            <label for="dokterNoHP" class="form-label">No. HP</label>
                            <input type="text" class="form-control" id="dokterNoHP" required>
                        </div>
                        <div class="mb-3">
                            <label for="dokterPoli" class="form-label">ID Poli</label>
                            <input type="text" class="form-control" id="dokterPoli" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btnSimpanDokter">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    

<!-- Modal for Tambah Pasien -->
<div class="modal fade" id="tambahPasienModal" tabindex="-1" aria-labelledby="tambahPasienModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahPasienModalLabel">Tambah Pasien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formTambahPasien">
                    <div class="mb-3">
                        <label for="pasienNama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="pasienNama" required>
                    </div>
                    <div class="mb-3">
                        <label for="pasienAlamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="pasienAlamat" required>
                    </div>
                    <div class="mb-3">
                        <label for="pasienNoKTP" class="form-label">No. KTP</label>
                        <input type="text" class="form-control" id="pasienNoKTP" required>
                    </div>
                    <div class="mb-3">
                        <label for="pasienNoHP" class="form-label">No HP</label>
                        <input type="text" class="form-control" id="pasienNoHP" required>
                    </div>
                    <div class="mb-3">
                        <label for="pasienPoli" class="form-label">No RM</label>
                        <input type="text" class="form-control" id="pasienPoli" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" id="btnSimpanPasien">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Modal for Tambah Poli -->
<div class="modal fade" id="tambahPoliModal" tabindex="-1" aria-labelledby="tambahPoliModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahPoliModalLabel">Tambah Data Poli</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formTambahPoli">
                    <div class="mb-3">
                        <label for="poliNama" class="form-label">Nama Poli</label>
                        <input type="text" class="form-control" id="poliNama" required>
                    </div>
                    <div class="mb-3">
                        <label for="poliKeterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="poliKeterangan" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnSimpanPoli">Simpan</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal for Tambah Obat -->
<div class="modal fade" id="tambahObatModal" tabindex="-1" aria-labelledby="tambahObatModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahObatModalLabel">Tambah Data Obat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formTambahObat">
                    <div class="mb-3">
                        <label for="obatNama" class="form-label">Nama Obat</label>
                        <input type="text" class="form-control" id="obatNama" required>
                    </div>
                    <div class="mb-3">
                        <label for="obatKategori" class="form-label">Kategori</label>
                        <input type="text" class="form-control" id="obatKategori" required>
                    </div>
                    <div class="mb-3">
                        <label for="obatHarga" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="obatHarga" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnSimpanObat">Simpan</button>
            </div>
        </div>
    </div>
</div>




    <script>
        document.getElementById('tambah-poli-btn').addEventListener('click', function() {
            new bootstrap.Modal(document.getElementById('tambahPoliModal')).show();});

        document.getElementById('tambah-obat-btn').addEventListener('click', function() {
            new bootstrap.Modal(document.getElementById('tambahObatModal')).show();});
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        window.onload = function() {
            // Menampilkan Dashboard sebagai default
            document.getElementById('dashboard-content').style.display = 'block';
            document.getElementById('dokter-content').style.display = 'none';
            document.getElementById('pasien-content').style.display = 'none';
            document.getElementById('poli-content').style.display = 'none';
            document.getElementById('obat-content').style.display = 'none';
        };

        // Menampilkan konten berdasarkan tab yang diklik
        document.getElementById('dashboard-tab').addEventListener('click', function() {
            document.getElementById('dashboard-content').style.display = 'block';
            document.getElementById('dokter-content').style.display = 'none';
            document.getElementById('pasien-content').style.display = 'none';
            document.getElementById('poli-content').style.display = 'none';
            document.getElementById('obat-content').style.display = 'none';
        });

        document.getElementById('dokter-tab').addEventListener('click', function() {
            document.getElementById('dokter-content').style.display = 'block';
            document.getElementById('dashboard-content').style.display = 'none';
            document.getElementById('pasien-content').style.display = 'none';
            document.getElementById('poli-content').style.display = 'none';
            document.getElementById('obat-content').style.display = 'none';
        });

        document.getElementById('pasien-tab').addEventListener('click', function() {
            document.getElementById('pasien-content').style.display = 'block';
            document.getElementById('dashboard-content').style.display = 'none';
            document.getElementById('dokter-content').style.display = 'none';
            document.getElementById('poli-content').style.display = 'none';
            document.getElementById('obat-content').style.display = 'none';
        });

        document.getElementById('poli-tab').addEventListener('click', function() {
            document.getElementById('poli-content').style.display = 'block';
            document.getElementById('dashboard-content').style.display = 'none';
            document.getElementById('dokter-content').style.display = 'none';
            document.getElementById('pasien-content').style.display = 'none';
            document.getElementById('obat-content').style.display = 'none';
        });

        document.getElementById('obat-tab').addEventListener('click', function() {
            document.getElementById('obat-content').style.display = 'block';
            document.getElementById('dashboard-content').style.display = 'none';
            document.getElementById('dokter-content').style.display = 'none';
            document.getElementById('pasien-content').style.display = 'none';
            document.getElementById('poli-content').style.display = 'none';
        });
    </script>
    <script>
        document.getElementById('tambah-dokter-btn').addEventListener('click', function() {
            new bootstrap.Modal(document.getElementById('tambahDokterModal')).show();
        });

        document.getElementById('btnSimpanDokter').addEventListener('click', function() {
            var namaDokter = document.getElementById('dokterNama').value;
            var alamatDokter = document.getElementById('dokterAlamat').value;
            var noHpDokter = document.getElementById('dokterNoHP').value;
            var idPoli = document.getElementById('dokterPoli').value;

            if (!namaDokter || !alamatDokter || !noHpDokter || !idPoli) {
                alert('Harap lengkapi semua data.');
                return;
            }
            fetch('tambah_dokter.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'nama_dokter=' + encodeURIComponent(namaDokter) +
                      '&alamat_dokter=' + encodeURIComponent(alamatDokter) +
                      '&no_hp_dokter=' + encodeURIComponent(noHpDokter) +
                      '&id_poli=' + encodeURIComponent(idPoli)
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                $('#tambahDokterModal').modal('hide');
                location.reload(); 
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
        $(document).on('click', '.btnHapusDokter', function() {
            var dokterId = $(this).data('id');
            if (confirm('Apakah Anda yakin ingin menghapus dokter ini?')) {
                $.post('hapus_dokter.php', { id: dokterId }, function(response) {
                    alert(response);
                    $('#dokter' + dokterId).remove();
                }).fail(function() {
                    alert('Terjadi kesalahan saat menghapus data.');
                });
            }
        });

         // Menampilkan modal tambah pasien
         document.getElementById('tambah-pasien-btn').addEventListener('click', function() {
            new bootstrap.Modal(document.getElementById('tambahPasienModal')).show();
        });

        // Menyimpan data pasien
        document.getElementById('btnSimpanPasien').addEventListener('click', function() {
            var namaPasien = document.getElementById('pasienNama').value;
            var alamatPasien = document.getElementById('pasienAlamat').value;
            var noktp = document.getElementById('pasienNoKTP').value;
            var noHpPasien = document.getElementById('pasienNoHP').value;
            var idPoli = document.getElementById('pasienPoli').value;

            if (!namaPasien || !alamatPasien || !noHpPasien || !idPoli) {
                alert('Harap lengkapi semua data.');
                return;
            }

            fetch('tambah_pasien.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'nama_pasien=' + encodeURIComponent(namaPasien) +
                      '&alamat_pasien=' + encodeURIComponent(alamatPasien) +
                      '&no_ktp=' + encodeURIComponent(noktp) +
                      '&no_hp_pasien=' + encodeURIComponent(noHpPasien) +
                      '&no_rm=' + encodeURIComponent(idPoli)
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                $('#tambahPasienModal').modal('hide');
                location.reload(); 
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        // Menghapus pasien
        $(document).on('click', '.btnHapusPasien', function() {
            var pasienId = $(this).data('id');
            if (confirm('Apakah Anda yakin ingin menghapus pasien ini?')) {
                $.post('hapus_pasien.php', { id: pasienId }, function(response) {
                    alert(response);
                    $('#pasien' + pasienId).remove();
                }).fail(function() {
                    alert('Terjadi kesalahan saat menghapus data.');
                });
            }
        });

        // Menyimpan data Poli
        document.getElementById('btnSimpanPoli').addEventListener('click', function() {
            var namaPoli = document.getElementById('poliNama').value;
            var keteranganPoli = document.getElementById('poliKeterangan').value;

            if (!namaPoli || !keteranganPoli) {
                alert('Harap lengkapi semua data.');
                return;
            }

            // Kirim data ke server menggunakan fetch
            fetch('tambah_poli.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'nama_poli=' + encodeURIComponent(namaPoli) +
                      '&keterangan_poli=' + encodeURIComponent(keteranganPoli) 
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                $('#tambahPasienModal').modal('hide');
                location.reload(); // Refresh tabel setelah data berhasil disimpan
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        // Menghapus pasien
        $(document).on('click', '.btnHapusPoli', function() {
            var poliId = $(this).data('id'); // Pastikan menggunakan poliId yang dikirim dari data-id tombol
            if (confirm('Apakah Anda yakin ingin menghapus poli ini?')) {
                $.post('hapus_poli.php', { id: poliId }, function(response) {
                    alert(response);
                    location.reload();
                    $('#poli' + poliId).remove(); // Menghapus baris terkait poli setelah penghapusan berhasil
                }).fail(function() {
                    alert('Terjadi kesalahan saat menghapus data.');
                });
            }
        });

        // Menyimpan data obat
        document.getElementById('btnSimpanObat').addEventListener('click', function() {
            var namaObat = document.getElementById('obatNama').value;
            var kategoriObat = document.getElementById('obatKategori').value;
            var hargaObat = document.getElementById('obatHarga').value;

            // Validasi
            if (!namaObat || !kategoriObat || !hargaObat) {
                alert('Harap lengkapi semua data.');
                return;
            }

            // Kirim data ke server menggunakan fetch
            fetch('tambah_obat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'nama_obat=' + encodeURIComponent(namaObat) +
                    '&kategori_obat=' + encodeURIComponent(kategoriObat) +
                    '&harga_obat=' + encodeURIComponent(hargaObat)
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Menampilkan pesan dari server
                $('#tambahObatModal').modal('hide'); // Menutup modal
                location.reload(); // Refresh halaman setelah data berhasil disimpan
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        $(document).on('click', '.btnHapusObat', function() {
            var obatId = $(this).data('id'); // Mendapatkan ID obat dari tombol yang diklik
            
            if (confirm('Apakah Anda yakin ingin menghapus obat ini?')) {
                // Mengirim data ID obat ke PHP untuk dihapus
                $.post('hapus_obat.php', { id: obatId }, function(response) {
                    alert(response); 
                    location.reload();
                    $('#obat' + obatId).remove(); // Menghapus baris obat dari tampilan setelah dihapus
                }).fail(function() {
                    alert('Terjadi kesalahan saat menghapus data obat.');
                });
            }
        });
    </script>
</body>

</html>
