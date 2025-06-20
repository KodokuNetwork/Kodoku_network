<?php
// ALWAYS the very first PHP line in your main entry point files
define('BASE_URL', 'https://store.kodoku.me/');

// --- TEMPORARY DEBUGGING LINES (REMOVE ON PRODUCTION!) ---
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
// --- END TEMPORARY DEBUGGING LINES ---

$bodyClass = 'login-bg';
include 'includes/header.php'; // This file now expects BASE_URL to be defined
?>

<style>
  /* Wrapper untuk panel login, memastikan posisinya benar */
  .login-panel-wrapper {
    display: flex;
    justify-content: center; /* Rata tengah di mobile */
    width: 100%;
    max-width: 390px; /* Lebar asli gambar panel Anda */
  }

  /* Panel responsif yang menjaga rasio aspek gambar.
     Kelas .login-panel sebelumnya diganti dengan ini. */
  .responsive-image-panel {
    position: relative;
    width: 100%;
    /* Rasio Aspek: (tinggi 380px / lebar 390px) * 100% = 97.44% */
    padding-bottom: 97.44%;
    height: 0;
    background: url('assets/images/loginpsd.png') no-repeat center center;
    background-size: contain;
  }

  /* Konten (form) di dalam panel harus diposisikan secara absolut */
  .panel-content {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    padding: 12% 8%; /* Padding dalam persentase agar ikut skala */
  }

  /* Logo box di dalam panel, dibuat responsif */
  .logo-box {
    width: 80%;
    height: 20%;
    background: url('assets/images/logopsd.png') no-repeat center center;
    background-size: contain;
    margin-bottom: 5%;
  }

  /* Tombol gambar yang responsif */
  .responsive-img-button {
    max-height: 50px; /* Tinggi maksimal */
    width: auto;      /* Lebar otomatis menyesuaikan proporsi */
    transition: transform 0.2s ease;
  }
  .responsive-img-button:hover {
    transform: scale(1.05);
  }

  /* Penyesuaian untuk layar medium ke atas (desktop) */
  @media (min-width: 768px) {
    .login-panel-wrapper {
      justify-content: flex-end; /* Rata kanan di layar besar */
    }
  }
</style>

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

<div class="fixed-logo" style="position: fixed; top: 24px; right: 32px; z-index: 10;">
  <img src="assets/images/logokodoku.png" alt="Kodoku Logo" style="height: 48px;">
</div>

<div class="d-flex flex-column min-vh-100 login-container">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-center flex-fill px-4 py-5 gap-5">
    
    <div class="text-white mb-5 mb-md-0">
      <h2 class="text-outline">
        Join us on an <span class="text-info">adventurous journey</span><br>
        with creative players out there
      </h2>
    </div>

    <div class="login-panel-wrapper">
      <div class="responsive-image-panel">
        
        <div class="panel-content">
          
          <div class="logo-box"></div>

          <form method="POST" action="login_process.php" class="w-100">
            <div class="mb-3 px-4">
              <input type="text" name="name" class="form-control text-white border-0" placeholder="Username"
                style="background-color: #1e1e1e; border-radius: 8px; padding: 10px 14px;">
            </div>

            <div class="d-flex gap-3 mt-4 justify-content-center">
              <button type="submit" name="version" value="java" class="btn p-0 border-0 bg-transparent">
                <img src="assets/images/java.png" alt="Java" class="responsive-img-button">
              </button>
              <button type="submit" name="version" value="bedrock" class="btn p-0 border-0 bg-transparent">
                <img src="assets/images/bedrock.png" alt="Bedrock" class="responsive-img-button">
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>