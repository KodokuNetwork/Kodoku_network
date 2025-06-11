<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/../config/database.php';

$success = null;
$error = null;

// === [UPLOAD CHANGELOG] ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['markdown'])) {
    $markdown = $_POST['markdown'];
    $lines = explode("\n", $markdown);
    $title = '';
    $sections = [];
    $currentSection = null;

    foreach ($lines as $line) {
        $line = trim($line);
        if (str_starts_with($line, '# ')) {
            $title = substr($line, 2);
        } elseif (str_starts_with($line, '## ')) {
            if ($currentSection) $sections[] = $currentSection;
            $currentSection = ['heading' => substr($line, 3), 'items' => []];
        } elseif (str_starts_with($line, '-')) {
            if ($currentSection) $currentSection['items'][] = trim(substr($line, 1));
        }
    }
    if ($currentSection) $sections[] = $currentSection;
    $json = ['title' => $title, 'sections' => $sections];
    $targetPath = __DIR__ . '/../data/changelog.json';

    if (!is_dir(dirname($targetPath))) {
        mkdir(dirname($targetPath), 0755, true);
    }

    if (file_put_contents($targetPath, json_encode($json, JSON_PRETTY_PRINT))) {
        $success = "✅ Changelog berhasil disimpan!";
    } else {
        $error = "❌ Gagal menyimpan changelog.";
    }
}

// === [NEWS CRUD] ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['news_title'])) {
    $title = $_POST['news_title'];
    $content = $_POST['news_content'];
    $image = $_FILES['news_image']['name'] ?? '';
    $imagePath = '';

    if (!empty($image)) {
        $uploadDir = __DIR__ . '/../assets/images/news/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $targetPath = $uploadDir . basename($image);
        if (move_uploaded_file($_FILES['news_image']['tmp_name'], $targetPath)) {
            $imagePath = basename($image);
        }
    }

    $stmt = $newsConn->prepare("INSERT INTO news (title, content, image) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $content, $imagePath);
    $stmt->execute();
    $success = "📰 Berita berhasil ditambahkan.";
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $newsConn->prepare("DELETE FROM news WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: dashboard.php");
    exit();
}
$news = $newsConn->query("SELECT * FROM news ORDER BY created_at DESC");

// === [REDEEM CODE CRUD] ===
if (isset($_POST['create_redeem'])) {
    $code = strtoupper(trim($_POST['code']));
    $discount = intval($_POST['discount_percent']);
    $expires_at = $_POST['expires_at'];
    $limit = !empty($_POST['usage_limit']) ? intval($_POST['usage_limit']) : null;

    $stmt = $redeemConn->prepare("INSERT INTO redeem_codes (code, discount_percent, expires_at, usage_limit) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sisi", $code, $discount, $expires_at, $limit);
    $stmt->execute();
    $success = "✅ Redeem code berhasil dibuat.";
}

if (isset($_GET['delete_code'])) {
    $id = intval($_GET['delete_code']);
    $stmt = $redeemConn->prepare("DELETE FROM redeem_codes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: dashboard.php");
    exit();
}
$codes = $redeemConn->query("SELECT * FROM redeem_codes ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Dashboard Admin</h2>
    <a href="logout.php" class="btn btn-outline-danger">Logout</a>
  </div>

  <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
  <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

  <!-- Changelog Upload -->
  <form method="POST">
    <div class="mb-3">
      <label for="markdown" class="form-label">ChangeLog</label>
      <textarea name="markdown" id="markdown" rows="10" class="form-control" required># Judul
## Update / Fixes
- text
- text</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Convert & Save</button>
  </form>

  <hr class="my-5">
  <h3>Kelola Berita</h3>
  <form method="POST" enctype="multipart/form-data" class="mb-4">
    <div class="mb-2">
      <input type="text" name="news_title" class="form-control" placeholder="Judul berita" required>
    </div>
    <div class="mb-2">
      <textarea name="news_content" class="form-control" rows="5" placeholder="Isi berita" required></textarea>
    </div>
    <div class="mb-2">
      <input type="file" name="news_image" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Tambah Berita</button>
  </form>

  <div class="row">
    <?php while ($n = $news->fetch_assoc()): ?>
      <div class="col-md-4 mb-3">
        <div class="card h-100">
          <?php if (!empty($n['image'])): ?>
            <img src="../assets/images/news/<?= htmlspecialchars($n['image']) ?>" class="card-img-top" alt="">
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($n['title']) ?></h5>
            <p class="card-text small"><?= substr(strip_tags($n['content']), 0, 100) ?>...</p>
            <a href="../news_detail.php?id=<?= $n['id'] ?>" class="btn btn-sm btn-outline-primary">Lihat</a>
            <a href="dashboard.php?delete=<?= $n['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus berita ini?')">Hapus</a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <!-- REDEEM CODE FORM -->
  <hr class="my-5">
  <h3>Kelola Redeem Code Diskon</h3>
  <form method="POST">
    <div class="row g-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label">Kode Redeem</label>
        <input type="text" name="code" class="form-control" required>
      </div>
      <div class="col-md-2">
        <label class="form-label">Diskon (%)</label>
        <input type="number" name="discount_percent" class="form-control" min="1" max="100" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Tanggal Kadaluarsa</label>
        <input type="datetime-local" name="expires_at" class="form-control" required>
      </div>
      <div class="col-md-2">
        <label class="form-label">Batas Penggunaan (opsional)</label>
        <input type="number" name="usage_limit" class="form-control" min="1">
      </div>
      <div class="col-md-2">
        <button type="submit" name="create_redeem" class="btn btn-primary w-100">Buat Redeem</button>
      </div>
    </div>
  </form>

  <table class="table table-bordered table-sm mt-4">
    <thead>
      <tr>
        <th>Kode</th>
        <th>Diskon (%)</th>
        <th>Kadaluarsa</th>
        <th>Pakai</th>
        <th>Batas</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($c = $codes->fetch_assoc()): ?>
      <tr>
        <td><strong><?= htmlspecialchars($c['code']) ?></strong></td>
        <td><?= $c['discount_percent'] ?>%</td>
        <td><?= $c['expires_at'] ?></td>
        <td><?= $c['usage_count'] ?></td>
        <td><?= $c['usage_limit'] ?? '∞' ?></td>
        <td>
          <a href="dashboard.php?delete_code=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus kode ini?')">Hapus</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
