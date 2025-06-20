<?php
define('BASE_URL', 'https://store.kodoku.me/');
$bodyClass = 'store-bg';

include 'includes/header.php';

include 'includes/navbar.php';



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

<style>
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
    padding-right: 3rem;
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
  .kodoku-btn a {
    background:transparent;
    color: #5A5A5A !important;
    text-decoration: none !important;
    font-size: 24px;
  }
  .changelog-content li {
    color: #CCCCCC !important;
  }
</style>

<div class="container py-5 text-white bg-black min-vh-100">

  <!-- Back Button -->
  <div class="kodoku-btn mb-5" style="margin-left: 3rem;">
    <img src="assets/images/back.png" alt="Cart" style="height: 16px; position: relative; top: -4px; margin-right: 15px;">
    <a href="news.php">Back to previous page</a>
  </div>


  <!-- Changelog Card -->
  <div class="row justify-content-center mb-5">
    <div class="col-11">
      <?php if (!empty($changelogData)): ?>
        <?php foreach ($changelogData as $entry): ?>
          <div class="kodoku-changelog-card d-flex align-items-stretch shadow-lg">
            <div class="changelog-image">
              <img src="assets/images/new-hero.png" alt="Changelog Image"
                  class="img-fluid h-100 w-100" style="object-fit: cover;">
            </div>
            <div class="changelog-text text-end text-white d-flex flex-column justify-content-center p-4 ms-auto">
              <h3 class="fw-bold mb-3">KODOKU CHANGELOG <?= htmlspecialchars($entry['title']) ?> HAS RELEASED</h3>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>



  <!-- Changelog Content -->

  <div class="row justify-content-center changelog-content">

    <div class="col-md-10">

      <?php if (!empty($changelogData)): ?>

        <?php foreach ($changelogData as $entry): ?>

          <h4 class="fw-bold mb-3">Changelog <?= htmlspecialchars($entry['title']) ?></h4>



          <?php if (!empty($entry['sections'])): ?>

            <?php foreach ($entry['sections'] as $section): ?>

              <p class="fw-semibold mb-1 mt-4"><?= htmlspecialchars($section['heading']) ?></p>

              <ul>

                <?php foreach ($section['items'] as $item): ?>

                  <li><?= htmlspecialchars($item) ?></li>

                <?php endforeach; ?>

              </ul>

            <?php endforeach; ?>

          <?php endif; ?>

        <?php endforeach; ?>

      <?php else: ?>

        <p class="text-muted">No changelog data available.</p>

      <?php endif; ?>

    </div>

  </div>

</div>



<?php include 'includes/footer.php'; ?>

<?php include 'includes/scripts.php'; ?>

