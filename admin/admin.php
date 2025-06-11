<?php
session_start();

// Load .env
require_once __DIR__ . '/../load_env.php';
loadEnv(__DIR__ . '/../.env');

if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Ambil dari .env
    $valid_username = getenv('ADMIN_USERNAME');
    $valid_password = getenv('ADMIN_PASSWORD');

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = 'Username atau password salah!';
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body class="bg-dark d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
      <h4 class="mb-3 text-center">Admin Login</h4>

      <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" name="username" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" name="password" required>
        </div>

        <button type="submit" class="btn btn-warning w-100">Login</button>
      </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
