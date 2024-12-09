<?php
include('../koneksi.php'); // Koneksi ke database

// Periksa apakah parameter ID dan jenis entitas (dokter, pasien, poli, obat) tersedia
if (isset($_GET['id']) && isset($_GET['type'])) {
    $entityId = intval($_GET['id']); // Pastikan ID adalah integer
    $entityType = $_GET['type']; // Jenis entitas (dokter, pasien, poli, obat)

    // Tentukan query sesuai dengan jenis entitas yang diedit
    if ($entityType == 'dokter') {
        // Query untuk mendapatkan data dokter
        $sql = "SELECT dokter.id, dokter.nama, dokter.alamat, dokter.no_hp, dokter.id_poli, poli.nama_poli 
                FROM dokter
                INNER JOIN poli ON dokter.id_poli = poli.id
                WHERE dokter.id = ?";
    } elseif ($entityType == 'pasien') {
        // Query untuk mendapatkan data pasien
        $sql = "SELECT id, nama, alamat, no_ktp ,no_hp FROM pasien WHERE id = ?";
    } elseif ($entityType == 'poli') {
        // Query untuk mendapatkan data poli
        $sql = "SELECT id, nama_poli, keterangan FROM poli WHERE id = ?";
    } elseif ($entityType == 'obat') {
        // Query untuk mendapatkan data obat
        $sql = "SELECT id, nama_obat, kemasan , harga FROM obat WHERE id = ?";
    }

    // Persiapkan dan jalankan query
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $entityId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Periksa apakah data ditemukan
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc(); // Ambil data entitas
        } else {
            echo "Data tidak ditemukan.";
            exit;
        }
    } else {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }
} else {
    echo "ID atau jenis entitas tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
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
        </div>
        <div class="main-content p-3 flex-grow-1">
            <h1>Selamat Datang, Admin!</h1>
    <div class="container mt-5">
        <h2>Edit Data <?php echo ucfirst($entityType); ?></h2>
        <form action="update.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
            <input type="hidden" name="type" value="<?php echo $entityType; ?>">

            <?php if ($entityType == 'dokter'): ?>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Dokter</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $data['nama']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo $data['alamat']; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="no_hp" class="form-label">No HP</label>
                    <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?php echo $data['no_hp']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="id_poli" class="form-label">Poli</label>
                    <select class="form-control" id="id_poli" name="id_poli" required>
                        <?php
                        // Query untuk mendapatkan semua poli
                        $sqlPoli = "SELECT id, nama_poli FROM poli";
                        $resultPoli = $conn->query($sqlPoli);

                        if ($resultPoli->num_rows > 0) {
                            while ($poli = $resultPoli->fetch_assoc()) {
                                $selected = $data['id_poli'] == $poli['id'] ? 'selected' : '';
                                echo "<option value='" . $poli['id'] . "' $selected>" . $poli['nama_poli'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>Poli tidak ditemukan</option>";
                        }
                        ?>
                    </select>
                </div>
            <?php elseif ($entityType == 'pasien'): ?>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Pasien</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $data['nama']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo $data['alamat']; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="no_ktp" class="form-label">No KTP</label>
                    <input type="text" class="form-control" id="no_ktp" name="no_ktp" value="<?php echo $data['no_ktp']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="no_hp" class="form-label">No HP</label>
                    <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?php echo $data['no_hp']; ?>" required>
                </div>
            <?php elseif ($entityType == 'poli'): ?>
                <div class="mb-3">
                    <label for="nama_poli" class="form-label">Nama Poli</label>
                    <input type="text" class="form-control" id="nama_poli" name="nama_poli" value="<?php echo $data['nama_poli']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="nama_poli" class="form-label">Keterangan</label>
                    <input type="text" class="form-control" id="keterangan" name="keterangan" value="<?php echo $data['keterangan']; ?>" required>
                </div>
            <?php elseif ($entityType == 'obat'): ?>
                <div class="mb-3">
                    <label for="nama_obat" class="form-label">Nama Obat</label>
                    <input type="text" class="form-control" id="nama_obat" name="nama_obat" value="<?php echo $data['nama_obat']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="kemasan" class="form-label">Kemasan</label>
                    <input type="text" class="form-control" id="kemasan" name="kemasan" value="<?php echo $data['kemasan']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="harga" class="form-label">Harga Obat</label>
                    <input type="number" class="form-control" id="harga" name="harga" value="<?php echo $data['harga']; ?>" required>
                </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="dashboard_admin.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>
