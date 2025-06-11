<?php

require_once 'config/database.php';  // akses koneksi $newsConn

$bodyClass = 'store-bg'; // if you're using this for header layout



// Ambil parameter id dari URL

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;



// Siapkan query

$stmt = $newsConn->prepare("SELECT title, content, image, created_at FROM news WHERE id = ?");

$stmt->bind_param("i", $id);

$stmt->execute();

$stmt->bind_result($title, $content, $image, $created_at);



// Fetch data

if (!$stmt->fetch()) {

    include 'includes/header.php';

    include 'includes/navbar.php';

    echo "<div class='container py-5 text-white bg-black min-vh-100'><h2 class='text-center'>❌ Berita tidak ditemukan!</h2></div>";

    include 'includes/footer.php';

    include 'includes/scripts.php';

    exit();

}

$stmt->close();



include 'includes/header.php';

include 'includes/navbar.php';

?>
<style>
  .kodoku-new-detail-card {
    position: relative;
    border-radius: 20px;
    background: #0C0C0C;
    border: 3px solid rgba(255, 255, 255, 0.08); /* border halus */
    padding: 2rem 1.5rem;
    box-shadow:
      inset 60px 0 60px -40px rgba(255, 255, 255, 0.06), /* efek putih kiri */
      inset -60px 0 60px -40px rgba(255, 255, 255, 0.06), /* efek putih kanan */
      0 8px 24px rgba(0, 0, 0, 0.4); /* shadow bawah normal */;
  }
  .kodoku-btn a {
    background:transparent;
    color: #5A5A5A !important;
    text-decoration: none !important;
    font-size: 24px;
  }
  .img-line img {
    width: 100%;
    max-width: 100%;
    display: block;
  }
  .card-body .text-muted {
  color: #5A5A5A !important;
  }
  .card-text {
    color: #CCCCCC !important;
  }

  .news-detail-img {
  max-width: 100%;
  height: auto;
  object-fit: contain;
  display: block;
  max-height: 400px; /* Cegah gambar membesar di desktop */
  margin-left: auto;
  margin-right: auto;
  }
  
  @media (max-width: 768px) {
    .news-detail-img {
      max-height: unset; /* Biar bebas tinggi di HP */
    }
  }
</style>


<div class="container py-5 text-white bg-black min-vh-100">

  <!-- Back Button -->
  <div class="kodoku-btn mb-5" style="margin-left: 0.1rem;">
    <img src="assets/images/back.png" alt="Cart" style="height: 16px; position: relative; top: -4px; margin-right: 15px;">
    <a href="news.php">Back to previous page</a>
  </div>



  <div class="kodoku-new-detail-card shadow text-white">
    <div class="d-flex flex-column align-items-center w-100 h-100">
      <?php
        $imagePath = !empty($image)
          ? 'assets/images/news/' . htmlspecialchars($image)
          : 'assets/images/placeholder.png';
      ?>
      <img src="<?= $imagePath ?>" 
        class="news-detail-img card-img-top mx-auto d-block mb-5"
        alt="Gambar berita">
    </div>

    <div class="card-body">
      <div class="img-line mb-3">
        <img src="assets/images/line.png">
      </div>
      <h1 class="card-title mb-1"><?= htmlspecialchars($title) ?></h1>
      <p class="text-muted small"><?= date('d M Y H:i', strtotime($created_at)) ?></p>
      <div class="card-text"><?= nl2br(htmlspecialchars($content)) ?></div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>

