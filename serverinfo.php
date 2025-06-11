<?php $bodyClass = 'store-bg'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<main class="flex-fill">
<div class="container py-5">
    <div class="card border-0 shadow-lg p-4" style="background-color: #e8ddc9; border-radius: 12px;">
        <div class="row align-items-center">
            <!-- Left Content -->
            <div class="col-md-8">
                <h2 class="fw-bold">Meet The Server</h2>
                <p class="text-muted">Learn more about the people or features that make our Minecraft server unique!</p>

                <!-- Player Info Section -->
                <div id="player-info">
                    <h4 class="fw-bold text-warning" id="player-name"></h4>
                    <span class="badge bg-warning text-dark mb-3" id="player-role"></span>
                    <p class="fs-5" id="player-desc">
                        Click on an avatar to learn more about the staff member!
                    </p>
                </div>

                <!-- Avatars -->
                <div class="d-flex mt-4" id="avatar-list">
                    <img src="https://minepic.org/avatar/32/ZoeCantik" class="rounded me-2 player-avatar" alt="avatar" data-name="ZoeCantik" data-role="OWNER" data-desc="Hi, I’m ZoeCantik, your friendly moderator. I help keep the chat clean and assist new players on their journey!" data-image="assets/images/etel.png">
                    <img src="https://minepic.org/avatar/32/etlwn" class="rounded me-2 player-avatar" alt="avatar" data-name="etlwn" data-role="CO-OWNER" data-desc="Hi, I’m etlwn, one of the builders. I design and construct the amazing structures you see around the server." data-image="assets/images/etel.png">
                    <img src="https://minepic.org/avatar/32/euqlip" class="rounded me-2 player-avatar" alt="avatar" data-name="euqlip" data-role="3D ARTIST" data-desc="Hi, I’m euqlip, always ready to help you with your questions and guide you through the server." data-image="assets/images/euq.png">
                    <img src="https://minepic.org/avatar/32/4NV_" class="rounded me-2 player-avatar" alt="avatar" data-name="4NV_" data-role="3D ARTIST" data-desc="Hi, I’m 4NV_, here to answer your questions and help you get started." data-image="assets/images/nv.png">
                    <img src="https://minepic.org/avatar/32/Blazevow" class="rounded me-2 player-avatar" alt="avatar" data-name="Blazevow" data-role="ADMINISTRATOR" data-desc="Hi, I’m Blazevow, the developer behind custom plugins and features that make our server unique." data-image="assets/images/staff/blazevow.png">
                    <img src="https://minepic.org/avatar/32/Senn0_" class="rounded me-2 player-avatar" alt="avatar" data-name="Senn0_" data-role="DEVELOPER" data-desc="Hi, I’m Senn0_, making sure everyone has a fun and fair experience on the server." data-image="assets/images/seno.png">
                    <img src="https://minepic.org/avatar/32/JukianaAzura" class="rounded me-2 player-avatar" alt="avatar" data-name="JukianaAzura" data-role="WEB DEVELOPER" data-desc="Hi, I’m JukianaAzura, passionate about building and creating new worlds for you to explore." data-image="assets/images/staff/jukianaazura.png">
                    <img src="https://minepic.org/avatar/32/ChokyC_" class="rounded me-2 player-avatar" alt="avatar" data-name="ChokyC_" data-role="BUILDER" data-desc="Hi, I’m ChokyC_, keeping the server safe and enjoyable for everyone." data-image="assets/images/staff/chokyc.png">
                </div>
            </div>

            <!-- Right Character Image -->
            <div class="col-md-4 text-center">
                <img id="player-image" src="assets/images/kodokuireng.png" class="img-fluid" style="max-height: 300px;" alt="Staff Image">
            </div>
        </div>
    </div>
</div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatars = document.querySelectorAll('.player-avatar');

    avatars.forEach(function(avatar) {
        avatar.addEventListener('click', function() {
            // Reset all others
            avatars.forEach(function(other) {
                other.classList.remove('selected');
            });

            // Select this one
            avatar.classList.add('selected');

            // Update info panel
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
