<?php
define('CAN_ACCESS', true);
require_once 'config.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("location: " . BASE_URL . "index.php");
    exit();
}

require_once 'app/rentController.php';
$rentController = new rentController();

// Ambil data user
if (!isset($_GET['id'])) {
    header("location: " . BASE_URL . "dashboard.php");
    exit();
}

$userId = $_GET['id'];
$user = $rentController->getUserDetails($userId);

if (!$user) {
    $_SESSION['error'] = "Pengguna tidak ditemukan.";
    header("location: " . BASE_URL . "dashboard.php");
    exit();
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username   = trim($_POST['username']);
    $fullName   = trim($_POST['full_name']);
    $email      = trim($_POST['email']);
    $role       = trim($_POST['role']);

    // Cegah admin menurunkan role dirinya sendiri
    if ($userId == $_SESSION['user_id'] && $role != 'admin') {
        $_SESSION['error'] = "Anda tidak dapat mengubah role Anda sendiri.";
        header("location: edit-users.php?id=" . $userId);
        exit();
    }

    if ($rentController->updateUser($userId, $username, $fullName, $email, $role)) {
        $_SESSION['success'] = "Data pengguna berhasil diperbarui.";
        header("location: view-user.php?id=" . $userId);
        exit();
    } else {
        $_SESSION['error'] = "Gagal memperbarui data pengguna.";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna | Sewa Apartemen.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-dark" href="<?php echo BASE_URL; ?>dashboard.php">Sewa Apartemen.id</a>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>
                        <span><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>

    </div>
</nav>

<main class="main-content" style="margin-top: 80px;">
    <div class="container-fluid">

        <a href="<?php echo BASE_URL; ?>view-user.php?id=<?php echo $userId; ?>" class="btn btn-secondary mb-4">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>

        <div class="card shadow">
            <div class="card-header">
                <h3><i class="fas fa-edit me-2"></i>Edit Pengguna</h3>
            </div>

            <div class="card-body">

                <?php if (isset($_SESSION['error'])) { ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php } ?>

                <form method="POST">

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="user"  <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
                            <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                    </button>

                </form>

            </div>
        </div>

    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
