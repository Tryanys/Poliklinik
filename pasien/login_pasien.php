<?php
session_start();

include('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
    $ktp = isset($_POST['noktp']) ? $_POST['noktp'] : '';
    $nohp = isset($_POST['nohp']) ? $_POST['nohp'] : '';

    $sql = "SELECT * FROM pasien WHERE nama = ? AND alamat = ? AND no_ktp = ? AND no_hp = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $alamat, $ktp, $nohp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['id_pasien'] = $row['id'];
        $_SESSION['nama'] = $row['nama'];
        header("Location: dashboard_pasien.php"); 
        exit;
    } else {
        $error_message = "Username, Password, No. KTP atau No. HP tidak valid.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card shadow-lg" style="max-width: 400px; width: 100%;">
            <div class="card-body">
                <h2 class="card-title text-center text-primary mb-4">Login Pasien</h2>
                <form action="#" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" placeholder="alamat" required>
                    </div>
                    <div class="mb-3">
                        <label for="noktp" class="form-label">No. KTP</label>
                        <input type="text" class="form-control" id="noktp" name="noktp" placeholder="Masukkan No. KTP" required>
                    </div>
                    <div class="mb-3">
                        <label for="nohp" class="form-label">No. HP</label>
                        <input type="text" class="form-control" id="nohp" name="nohp" placeholder="Masukkan No. HP" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger mt-3" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <div class="text-center mt-3">
                    <a href="registrasi.php" class="text-decoration-none">belum punya akun?</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
