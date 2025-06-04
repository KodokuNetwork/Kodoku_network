<?php $bodyClass = 'store-bg'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="container py-5 text-white bg-black min-vh-100">
  <div class="text-center mb-4">
    <h2 class="fw-bold">K-News</h2>
    <p class="text-secondary">Some news information and updates are here</p>
  </div>

  <div class="row justify-content-center mb-4">
    <div class="col-md-10">
      <div class="card bg-dark border-0 shadow overflow-hidden">
        <div class="row g-0">
          <div class="col-md-6">
            <img src="assets/images/news-hero.jpg" class="img-fluid h-100 w-100 object-fit-cover" alt="News Image">
          </div>
          <div class="col-md-6 d-flex flex-column justify-content-center p-4">
            <h5 class="fw-bold text-white">KODOKU CHANGELOG<br>0.94A HAS RELEASED</h5>
            <a href="changelog.php" class="text-secondary mb-0 d-inline-block text-decoration-none">
              Check information details &gt;
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Future Features -->
  <div class="row g-4 justify-content-center">
    <?php for ($i = 0; $i < 4; $i++): ?>
      <div class="col-12 col-md-5 col-lg-4">
        <div class="card bg-dark border-0 text-center h-100 p-3">
          <img src="assets/images/feature-coming.png" class="mx-auto" alt="Future Feature" style="max-height: 140px;">
          <p class="mt-3 mb-0 text-white">This feature will be available<br>in the future</p>
        </div>
      </div>
    <?php endfor; ?>
  </div>

  <div class="text-center mt-5">
    <button class="btn btn-secondary rounded-pill px-4 py-2">Load More</button>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>