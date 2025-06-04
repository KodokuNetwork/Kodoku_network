<?php $bodyClass = 'store-bg'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="container py-5 text-white bg-black min-vh-100">
  <!-- Back link -->
  <div class="mb-3">
    <a href="news.php" class="text-decoration-none text-secondary">
      <i class="bi bi-arrow-left"></i> Back to previous page
    </a>
  </div>

  <!-- Hero Banner -->
  <div class="row justify-content-center mb-5">
    <div class="col-md-10">
      <div class="card bg-dark border-0 shadow overflow-hidden">
        <div class="row g-0">
          <div class="col-md-6">
            <img src="assets/images/news-hero.jpg" class="img-fluid h-100 w-100 object-fit-cover" alt="News Image">
          </div>
          <div class="col-md-6 d-flex flex-column justify-content-center p-4">
            <h5 class="fw-bold text-white">KODOKU CHANGELOG<br>0.94A HAS RELEASED</h5>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Changelog Content -->
  <div class="row justify-content-center">
    <div class="col-md-10">
      <h4 class="fw-bold mb-3">Kodoku Changelog 0.94A Has Released!</h4>

      <p class="mb-1"><strong>Mods Update v 2.43</strong><br>
      Kodoku Changelog 0.94A</p>

      <p class="fw-semibold mb-1 mt-4">Ingame Server update</p>
      <ul>
        <li>Added maintenance mode</li>
        <li>Added bulk-selling npc, at *95% rate (name: asep)</li>
        <li>Added grave run mechanic, you get 20 second speed & ress buff after respawn</li>
        <li>Added door tag on lightman’s currency display item, to make sure everybody can trade</li>
        <li>Bugfix apple press block</li>
      </ul>

      <p class="fw-semibold mb-1">Adjustment for damage formula and rebalance for low-mid game experience</p>
      <ul>
        <li>Rebalance wine to make more sensible, easy to make but have had low duration</li>
        <li>Adjustment silverfish create farm, haunting at rate 15% per block</li>
        <li>Silverfish farm was too overpower so we make tweak of it to make same as vanilla farm. We want silverfish farm was machine only exclusive</li>
      </ul>
    </div>
  </div>
</div>


<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>