<?php
define('BASE_URL', 'https://store.kodoku.me/');

$bodyClass = 'store-bg';
include 'includes/header.php';
include 'includes/navbar.php';
?>

<main class="flex-fill">
  <div class="container py-5">
    <div class="p-4 rounded-4" style="background: #0f0f0f; border: 2px solid #FFD966;">
      <div class="row align-items-center">
        <div class="col-md-8">
          <h2 class="bit-font text-white mb-2" style="font-size: 2.2rem;">Meet The Server</h2>
          <p style="color: #FFD966; font-size: 1rem;">Get to know the team behind the Kodoku Minecraft server.</p>

          <div id="player-info">
            <h4 class="fw-bold text-warning" id="player-name" style="text-shadow: 0 0 4px rgba(0,0,0,0.5);"></h4>
            <span class="badge bg-warning text-dark mb-3" id="player-role" style="text-shadow: 0 0 3px rgba(0,0,0,0.4);"></span>
            <p class="fs-5 text-white" id="player-desc" style="text-shadow: 0 0 3px rgba(0,0,0,0.4);">
              Click on an avatar to learn more about the staff member!
            </p>
          </div>

          <div class="d-flex flex-wrap gap-3 mt-3" id="avatar-list">
            <img src="https://minepic.org/avatar/32/ZoeCantik" class="player-avatar" alt="avatar"
              data-name="ZoeCantik" data-role="OWNER"
              data-desc="Hi, I’m ZoeCantik, the Owner of the server. I ensure everything runs smoothly, keep the chat clean, and help guide new players on their journey!"
              data-image="<?= BASE_URL ?>assets/images/etel.png">

            <img src="https://minepic.org/avatar/32/etlwn" class="player-avatar" alt="avatar"
              data-name="etlwn" data-role="CO-OWNER"
              data-desc="Hi, I’m etlwn, the Co-Owner. I help manage the team for the server."
              data-image="<?= BASE_URL ?>assets/images/etel.png">

            <img src="https://minepic.org/avatar/32/euqlip" class="player-avatar" alt="avatar"
              data-name="euqlip" data-role="3D ARTIST"
              data-desc="Hi, I’m euqlip, a 3D Artist focused on creating detailed models and visuals. I’m also happy to guide and help players!"
              data-image="<?= BASE_URL ?>assets/images/euq.png">

            <img src="https://minepic.org/avatar/32/4NV_" class="player-avatar" alt="avatar"
              data-name="4NV_" data-role="3D ARTIST"
              data-desc="Hi, I’m 4NV_, a 3D Artist working on custom models and builds. I’m here to assist you and ensure a welcoming experience."
              data-image="<?= BASE_URL ?>assets/images/nv.png">

            <img src="https://minepic.org/avatar/32/Blazevow" class="player-avatar" alt="avatar"
              data-name="Blazevow" data-role="ADMINISTRATOR"
              data-desc="Hi, I’m Blazevow, an Administrator and the main plugin developer. I create and maintain custom features that power our server."
              data-image="<?= BASE_URL ?>assets/images/staff/blazevow.png">

            <img src="https://minepic.org/avatar/32/Senn0_" class="player-avatar" alt="avatar"
              data-name="Senn0_" data-role="DEVELOPER"
              data-desc="Hi, I’m Senn0_, a Backend Developer ensuring everything works smoothly. I focus on fair gameplay and performance optimization."
              data-image="<?= BASE_URL ?>assets/images/seno.png">

            <img src="https://minepic.org/avatar/32/JukianaAzura" class="player-avatar" alt="avatar"
              data-name="JukianaAzura" data-role="WEB DEVELOPER"
              data-desc="Hi, I’m JukianaAzura, the Web Developer behind our website and store systems. I love building interactive web experiences for the community."
              data-image="<?= BASE_URL ?>assets/images/staff/jukianaazura.png">

            <img src="https://minepic.org/avatar/32/ChokyC_" class="player-avatar" alt="avatar"
              data-name="ChokyC_" data-role="BUILDER"
              data-desc="Hi, I’m ChokyC_, one of the Builders on the server. I help design creative builds and keep the environment fun and safe for everyone."
              data-image="<?= BASE_URL ?>assets/images/staff/chokyc.png">
          </div>
        </div>

        <div class="col-md-4 text-center">
          <img id="player-image" src="<?= BASE_URL ?>assets/images/kodokuireng.png" class="img-fluid rounded shadow"
            style="max-height: 300px;" alt="Staff Image">
        </div>
      </div>
    </div>
  </div>
</main>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;700&display=swap');

  .bit-font {
    font-family: 'Minecraftia', 'Press Start 2P', cursive;
    letter-spacing: 1px;
  }

  body, p, .text-white, .fs-5 {
    font-family: 'Rubik', sans-serif !important;
    font-weight: 400;
  }

  h2, h4, .fw-bold {
    font-family: 'Rubik', sans-serif;
    font-weight: 700;
  }

  .text-white {
    color: #f4f4f4 !important;
  }

  .player-avatar {
    width: 48px;
    height: 48px;
    cursor: pointer;
    transition: transform 0.2s ease-in-out, box-shadow 0.2s;
    border-radius: 0.4rem;
  }

  .player-avatar:hover {
    transform: scale(1.1);
    box-shadow: 0 0 10px #FFD966;
  }

  .player-avatar.selected {
    border: 2px solid #FFD966;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const avatars = document.querySelectorAll('.player-avatar');

    avatars.forEach(function (avatar) {
      avatar.addEventListener('click', function () {
        avatars.forEach(function (other) {
          other.classList.remove('selected');
        });

        avatar.classList.add('selected');

        document.getElementById('player-name').textContent = avatar.getAttribute('data-name');
        document.getElementById('player-role').textContent = avatar.getAttribute('data-role');
        document.getElementById('player-desc').textContent = avatar.getAttribute('data-desc');
        document.getElementById('player-image').src = avatar.getAttribute('data-image');
      });
    });
  });
</script>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>
