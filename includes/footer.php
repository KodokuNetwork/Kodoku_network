<footer class="text-light py-3" style="background-color: #1c1c1c; font-size: 14px;">
  <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
    <!-- Left: Logo + Text -->
    <div class="d-flex align-items-center gap-3">
      <img src="assets/images/logokodoku.png" alt="Kodoku Logo" style="height: 28px; filter: brightness(0.8);">
      <span style="color: rgba(255,255,255,0.75);">
        Need any help? We really appreciate your advice and suggestion!
      </span>
    </div>
    <!-- Right: Contact + Icons -->
    <div class="d-flex align-items-center gap-3">
      <span style="color: rgba(255,255,255,0.75); font-style: italic;">Support the server ❤️</span>
      <a href="https://discord.gg/WbafFVcVdv" class="text-decoration-none" style="color: rgba(255,255,255,0.75);">Contact Support</a>
      <a href="https://discord.gg/WbafFVcVdv"><img src="assets/images/discord1.png" alt="Discord" style="height: 20px; opacity: 0.75;"></a>
    </div>
  </div>
</footer>

<!-- Floating Donate Button -->
<button id="donateBtn" class="btn btn-warning rounded-circle shadow-lg"
  style="position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; z-index: 9999;">
  💖
</button>
<!-- Donation Modal -->
<div class="modal fade" id="donationModal" tabindex="-1" aria-labelledby="donationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form id="donationForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="donationModalLabel">Support the Server</h5>
      </div>
      <div class="modal-body">
        <p class="mb-3 text-muted">
          Your donation will help support the server’s growth, maintenance, and future events.
          Every bit of help is truly appreciated ❤️
        </p>
        <label for="amount" class="form-label fw-bold">Donation Amount (Min Rp 15.000)</label>
        <input type="number" class="form-control" id="amount" name="amount" min="15000" required placeholder="e.g. 15000">
        <small class="text-muted d-block mt-1">Payments are processed securely via Midtrans.</small>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Donate</button>
      </div>
    </form>
  </div>
</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Snap.js + Donation Script -->
<script src="https://app.midtrans.com/snap/snap.js" data-client-key="<?= getenv('MIDTRANS_CLIENT_KEY') ?>"></script>
<script>
  document.getElementById('donateBtn').addEventListener('click', () => {
    const modal = new bootstrap.Modal(document.getElementById('donationModal'));
    modal.show();
  });
  document.getElementById('donationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const amount = parseInt(document.getElementById('amount').value);
    if (amount < 15000) {
      alert("Minimum donation is Rp 15.000");
      return;
    }
    fetch('donation_process.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          amount: amount
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.token) {
          snap.pay(data.token, {
            onSuccess: function(result) {
              alert("✅ Thank you for your support!");
            },
            onPending: function(result) {
              alert("⏳ Waiting for payment...");
            },
            onError: function(result) {
              alert("❌ Payment failed.");
            }
          });
        } else {
          alert("❌ Failed to generate payment token.");
        }
      });
  });
</script>
</body>

</html>