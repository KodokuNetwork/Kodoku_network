<?php
$bodyClass = 'store-bg';
require_once 'config/database.php'; // untuk akses $newsConn

// Ambil semua berita dari DB
$newsResult = $newsConn->query("SELECT * FROM news ORDER BY created_at DESC");

$changelogPath = __DIR__ . '/data/changelog.json';

$changelogData = [];

if (file_exists($changelogPath)) {

    $json = file_get_contents($changelogPath);

    $decoded = json_decode($json, true);



    // If it's a single object, wrap it in an array

    if (isset($decoded['title'])) {

        $changelogData = [$decoded];

    } elseif (is_array($decoded)) {

        $changelogData = $decoded;

    }

}

?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<style>
  .kodoku-new-card {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    text-align: center;
    padding: 2rem 1.5rem;
    border-radius: 20px;
    background: #0C0C0C;
    border: 2px solid rgba(255, 255, 255, 0.08);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    min-height: 100px;
    height: 100%;
  }


  .kodoku-card-title {
    font-size: 1.05rem;
    margin-top: 0;
    margin-bottom: 0.75rem;
    text-align: center;
    word-break: break-word;
  }
  .kodoku-new-card:hover {
    transform: scale(1.02);
  }
  .news-img {
    width: 180px;
    height: 180px;
    object-fit: contain;
    border-radius: 10px;
  }
  .kodoku-new-card img {
    max-width: 120px;
    border-radius: 10px;
  }
  .kodoku-new-card p {
    font-size: 0.95rem;
    line-height: 1.4;
  }
  
  .content-wrapper {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    width: 100%;
  }

  .kodoku-changelog-card {
    position: relative;
    border-radius: 20px;
    background: #000;
    border: 3px solid rgba(255, 255, 255, 0.08); /* border halus */
    box-shadow:
      inset 60px 0 60px -40px rgba(255, 255, 255, 0.06), /* efek putih kiri */
      inset -60px 0 60px -40px rgba(255, 255, 255, 0.06), /* efek putih kanan */
      0 8px 24px rgba(0, 0, 0, 0.4); /* shadow bawah normal */;
    overflow: hidden;
  }

  .changelog-text {
    width: 45%;
    padding-right: 1rem !important;
  }

  .cursor-radial {
    position: absolute;
    pointer-events: none;
    width: 270px;
    height: 270px;
    border-radius: 50%;
    opacity: 0;
    transition: opacity 0.3s;
    z-index: 10;
    /* Lebih halus dan soft: */
    background: radial-gradient(
      circle,
      rgba(255,255,255,0.07) 0%,
      rgba(120,160,255,0.04) 35%,
      rgba(47,58,87,0.03) 70%,
      rgba(0,0,0,0) 100%
    );
    filter: blur(3px); /* tambah blur sedikit */
  }
  .kodoku-changelog-card:hover .cursor-radial {
    opacity: 1;
  }

  @media (max-width: 768px) {
    .kodoku-changelog-card {
      flex-direction: column !important;
      height: auto;
    }

    .changelog-image {
      order: -1 !important; /* Pastikan gambar di atas */
      width: 100% !important;
      border-radius: 0 !important;
    }
    .changelog-text {
      width: 100% !important;
      border-radius: 0 !important;
      padding: 1rem !important;
    }

    .changelog-text {
      text-align: center !important;
    }
    .changelog-image img {
      height: auto !important;
      max-height: 250px;
    }
  }
  .changelog-image {
    position: relative;
    width: 120%;
    height: 100%;
    overflow: hidden;
    border-top-left-radius: 20px;
    border-bottom-left-radius: 20px;
  }

  .changelog-image::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    width: 40%;
    height: 100%;
    background: linear-gradient(to right, rgba(24, 24, 24, 0) 0%, #000000 100%);
    pointer-events: none;
    z-index: 2;
  }
</style>

<div class="container py-5 text-white bg-black min-vh-100">
  <div class="text-center mb-3">
    <h1 class="bit-font">K-News</h1>
    <p class="text-secondary">Some news information and updates are here</p>
  </div>

  <!-- Changelog Card -->
  <div class="row justify-content-center mb-5">
    <div class="col-11">
      <?php if (!empty($changelogData)): ?>
        <?php foreach ($changelogData as $entry): ?>
          <div class="kodoku-changelog-card d-flex align-items-stretch shadow-lg">
            <div class="cursor-radial"></div>
            <div class="changelog-image">
              <img src="assets/images/new-hero.png" alt="Changelog Image"
                  class="img-fluid h-100 w-100" style="object-fit: cover;">
            </div>
            <div class="changelog-text text-end text-white d-flex flex-column justify-content-center p-4 ms-auto">
              <h3 class="fw-bold mb-3">KODOKU CHANGELOG <?= htmlspecialchars($entry['title']) ?> HAS RELEASED</h3>
              <a href="changelog.php" class="text-decoration-none text-secondary fs-5">Check information details &gt;</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <!-- News Cards -->
  <div class="row g-4 justify-content-center">
    <?php if ($newsResult && $newsResult->num_rows > 0): ?>
      <?php while ($row = $newsResult->fetch_assoc()): ?>
        <div class="col-12 col-md-6 col-lg-6">
          <div class="kodoku-new-card text-white">
          

            <!-- GAMBAR -->
            <div class="d-flex flex-column align-items-center w-100 h-100">
              <?php
                $imagePath = !empty($row['image'])
                  ? 'assets/images/news/' . htmlspecialchars($row['image'])
                  : 'assets/images/placeholder.png';
              ?>
              <img src="<?= $imagePath ?>" class="news-img" alt="News Image">
            </div>

            <!-- JUDUL (moved to top) -->
            <h5 class="kodoku-card-title fw-bold text-break px-2 mt-0 mb-3">
              <?= htmlspecialchars($row['title']) ?>
            </h5>

            <!-- TOMBOL -->
            <div class="mt-auto pt-3">
              <a href="news_detail.php?id=<?= $row['id'] ?>" class="btn btn-outline-secondary btn-sm">Baca Selengkapnya</a>
            </div>

          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <?php for ($i = 0; $i < 4; $i++): ?>
        <div class="col-12 col-md-6 col-lg-6">
          <div class="kodoku-new-card text-white text-center">
            <h5 class="kodoku-card-title fw-bold text-break px-2 mt-0 mb-3">Coming Soon</h5>
            <img src="assets/images/placeholder.png" class="news-img mb-3" alt="Coming Soon">
            <p class="text-secondary">This feature will be available<br>in the future</p>
          </div>
        </div>
      <?php endfor; ?>
    <?php endif; ?>
  </div>


  <!-- Load More Placeholder -->
  <div class="text-center mt-5">
    <button class="btn btn-secondary rounded-pill px-4 py-2" disabled>Load More</button>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>

<script>
document.querySelectorAll('.kodoku-changelog-card').forEach(card => {
  const radial = card.querySelector('.cursor-radial');
  card.addEventListener('mousemove', function(e) {
    const rect = card.getBoundingClientRect();
    const x = e.clientX - rect.left - radial.offsetWidth/2;
    const y = e.clientY - rect.top - radial.offsetHeight/2;
    radial.style.left = x + 'px';
    radial.style.top = y + 'px';
  });
  card.addEventListener('mouseleave', function() {
    radial.style.opacity = 0;
  });
  card.addEventListener('mouseenter', function() {
    radial.style.opacity = 1;
  });
});


</script>