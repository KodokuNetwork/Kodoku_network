<?php $bodyClass = 'login-bg'; ?>
<?php include 'includes/header.php'; ?>

<?php if (isset($_GET['error'])): ?>
  <div class="container position-absolute mt-4" style="z-index: 1050; top: 24px; left: 50%; transform: translateX(-50%);">
    <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
      <?php
      switch ($_GET['error']) {
        case 'empty_name':
          echo "Username cannot be empty.";
          break;
        case 'invalid_bedrock_name':
          echo "Bedrock usernames must start with a dot (e.g., .username).";
          break;
        case 'invalid_java_name':
          echo "Java usernames must not start with a dot.";
          break;
        case 'invalid_version':
          echo "Invalid game version selected.";
          break;
        default:
          echo "An unknown error occurred.";
      }
      ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
<?php endif; ?>

<!-- Logo pojok kanan atas -->
<div style="position: fixed; top: 24px; right: 32px; z-index: 10;">
  <img src="assets/images/logokodoku.png" alt="Kodoku Logo" style="height: 48px;">
</div>

<!-- Konten Login -->
<div class="d-flex flex-column min-vh-100 login-container">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-center flex-fill px-4 py-5">

    <!-- Kiri: Teks Selamat Datang -->
    <div class="text-white mb-5 mb-md-0">
      <h2 class="text-outline">
        Join us on an <span class="text-info">adventurous journey</span><br>
        with creative players out there
      </h2>
    </div>

    <!-- Kanan: Login Panel -->
    <div class="d-flex justify-content-center justify-content-md-end w-100">
      <div class="position-relative" style="background: url('assets/images/loginpsd.png') no-repeat center center; background-size: cover; width: 390px; height: 380px; padding: 40px 20px 20px 20px;">
        <div class="text-center">
          <!-- Logo box -->
          <div class="mb-4" style="background: url('assets/images/logopsd.png') no-repeat center center; background-size: contain; height: 70px;">
          </div>

          <!-- Form -->
          <form method="POST" action="login_process.php">
            <div class="mb-3 px-4">
              <input type="text" name="name" class="form-control text-white border-0" placeholder="Username"
                style="background-color: #1e1e1e; border-radius: 8px; padding: 10px 14px;">
            </div>

            <div class="d-flex gap-2 mt-4 justify-content-center">
              <button type="submit" name="version" value="java" class="btn p-0 border-0 bg-transparent">
                <img src="assets/images/java.png" alt="Java" style="height: 50px;">
              </button>
              <button type="submit" name="version" value="bedrock" class="btn p-0 border-0 bg-transparent">
                <img src="assets/images/bedrock.png" alt="Bedrock" style="height: 50px;">
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php include 'includes/scripts.php'; ?>