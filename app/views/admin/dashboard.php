<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['profile_error'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($_SESSION['profile_error']) ?></div>
    <?php unset($_SESSION['profile_error']); ?>
<?php endif; ?>
<div class="hero-dashboard">
  <div class="hero-left">
    <div class="hero-profile">
      <div class="hero-image" style="position:relative;">
        <?php if (!empty($currentUser['profile_picture']) && file_exists($currentUser['profile_picture'])): ?>
          <img src="/<?= htmlspecialchars($currentUser['profile_picture']) ?>" alt="Profile">
        <?php else: ?>
          <img src="https://i.pravatar.cc/200?img=12" alt="Profile">
        <?php endif; ?>
        <button onclick="openProfileModal()" style="position:absolute; bottom:5px; right:5px; width:34px; height:34px; border-radius:50%; background:#2563eb; color:#fff; border:2px solid #fff; cursor:pointer;" title="Edit Profile">
          <i class="fas fa-pen" style="font-size:13px;"></i>
        </button>
      </div>

      <div>
        <div class="hero-badge">Admin Panel</div>
        <h1 class="hero-name"><?= htmlspecialchars($currentUser['name'] ?? 'Admin') ?></h1>
        <p class="hero-role">Community Engagement Director</p>
      </div>
    </div>
  </div>

  <div class="hero-right">
     <div class="hero-campus" 
     onclick="openMapModal()" 
     style="cursor:pointer; transition:0.25s;"
     onmouseover="this.style.background='rgba(255,255,255,0.22)'"
     onmouseout="this.style.background='rgba(255,255,255,0.12)'">
    <i class="fas fa-location-dot"></i> ISU Cabagan Campus
</div>
    <div class="hero-date"><i class="fas fa-calendar"></i>Academic Year:  <?= htmlspecialchars($_SESSION['academic_year_label'] ?? 'Academic Year') ?></div>
    <div>
      <button onclick="openProfileModal()" style="padding:10px 18px; border:none; border-radius:8px; background:#fff; color:#183153; font-weight:600; cursor:pointer;">
        <i class="fas fa-user-edit"></i> Edit Profile
      </button>
    </div>
  </div>
</div>

<!-- STATISTICS -->
<!-- STATISTICS -->
<div class="modern-stats">
  <!-- TOTAL -->
  <div class="modern-card green-card"
    onclick="window.location.href='/admin/monitoring'">
    <div class="modern-icon"><i class="fas fa-folder-open"></i></div>
    <div class="modern-number"><?= $total ?? 0 ?></div>
    <div class="modern-title">Total Projects</div>
    <div class="modern-progress">
      <div class="progress-bar progress-green"></div>
    </div>
  </div>

  <!-- ONGOING -->
  <div class="modern-card blue-card"
    onclick="window.location.href='/admin/monitoring?status=ongoing'">
    <div class="modern-icon"><i class="fas fa-spinner"></i></div>
    <div class="modern-number"><?= $ongoing ?? 0 ?></div>
    <div class="modern-title">On-going</div>
    <div class="modern-progress">
      <div class="progress-bar progress-blue"></div>
    </div>
  </div>

  <!-- COMPLETED -->
  <div class="modern-card orange-card"
    onclick="window.location.href='/admin/monitoring?status=completed'">
    <div class="modern-icon"><i class="fas fa-circle-check"></i></div>
    <div class="modern-number"><?= $completed ?? 0 ?></div>
    <div class="modern-title">Completed</div>
    <div class="modern-progress">
      <div class="progress-bar progress-orange"></div>
    </div>
  </div>
</div>

<!-- PDF DISPLAY -->
<div id="pdfDisplay"></div>



<!-- ANALYTICS -->
<div class="analytics-grid">
  <div class="analytics-card">
    <div class="analytics-header">
      <h3>
        <i class="fas fa-chart-bar"></i>
        Extension Projects per College (Approved Proposals)
      </h3>
    </div>
    <canvas id="programChart"></canvas>
  </div>
</div>
<!-- Profile Edit Modal -->
<div id="profileModal" class="modal" style="display:none;">
  <div class="modal-content" style="max-width:500px;">
    <div class="modal-header">
      <h3>Edit Profile</h3>
      <button class="close-modal" onclick="closeProfileModal()">&times;</button>
    </div>

    <?php if (isset($_SESSION['profile_error'])): ?>
      <div style="color:red; margin-bottom:15px;"><?= htmlspecialchars($_SESSION['profile_error']) ?></div>
      <?php unset($_SESSION['profile_error']); ?>
    <?php endif; ?>

    <form method="POST" action="/admin/profile/update" enctype="multipart/form-data">
      <div style="margin-bottom:15px;">
        <label style="font-weight:600;">Full Name</label>
        <input type="text" name="name" class="form-input" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;"
          value="<?= htmlspecialchars($currentUser['name'] ?? '') ?>" required>
      </div>

      <div style="margin-bottom:15px;">
        <label style="font-weight:600;">Email Address</label>
        <input type="email" name="email" class="form-input" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;"
          value="<?= htmlspecialchars($currentUser['email'] ?? '') ?>" required>
      </div>

      <div style="margin-bottom:15px;">
        <label style="font-weight:600;">New Password (leave blank to keep current)</label>
        <input type="password" name="password" class="form-input" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
      </div>

      <div style="margin-bottom:15px;">
        <label style="font-weight:600;">Profile Picture</label>
        <?php if (!empty($currentUser['profile_picture']) && file_exists($currentUser['profile_picture'])): ?>
          <div style="margin-bottom:8px;">
            <img src="/<?= htmlspecialchars($currentUser['profile_picture']) ?>"
              style="width:60px; height:60px; border-radius:50%; object-fit:cover; border:2px solid #e5e7eb;">
          </div>
        <?php endif; ?>
        <input type="file" name="profile_picture" accept="image/*" style="width:100%; padding:10px;">
      </div>

      <div style="display:flex; gap:10px; margin-top:20px;">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        <button type="button" class="btn btn-secondary" onclick="closeProfileModal()">Cancel</button>
      </div>
    </form>
  </div>
</div>
<!-- MAP MODAL -->
<div id="mapModal" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:900px; width:90%; padding:20px;">
        <div class="modal-header">
            <h3><i class="fas fa-map-marker-alt"></i> ISU Cabagan Campus Location</h3>
            <button class="close-modal" onclick="closeMapModal()">&times;</button>
        </div>

        <div style="border-radius:12px; overflow:hidden; height:500px;">
            <iframe 
                src="https://www.google.com/maps?q=Isabela+State+University+Cabagan+Campus&output=embed"
                width="100%" 
                height="500" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

        <div style="margin-top:15px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
            <div>
                <strong>Isabela State University — Cabagan Campus</strong><br>
                <span style="font-size:13px; color:#6b7280;">
                    Cabagan, Isabela, Philippines
                </span>
            </div>
            <a href="https://www.google.com/maps/search/?api=1&query=Isabela+State+University+Cabagan+Campus" 
               target="_blank" 
               rel="noopener"
               class="btn btn-primary">
                <i class="fas fa-external-link-alt"></i> Open in Google Maps
            </a>
        </div>
    </div>
</div>
<script>
  function openProfileModal() {
    document.getElementById('profileModal').style.display = 'flex';
  }

  function closeProfileModal() {
    document.getElementById('profileModal').style.display = 'none';
  }
    function openMapModal() {
    document.getElementById('mapModal').style.display = 'flex';
}

function closeMapModal() {
    document.getElementById('mapModal').style.display = 'none';
}

// Close on outside click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('mapModal');
    if (e.target === modal) modal.style.display = 'none';
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('mapModal');
        if (modal) modal.style.display = 'none';
    }
});
  document.addEventListener('click', function(e) {
    const modal = document.getElementById('profileModal');
    if (e.target === modal) modal.style.display = 'none';
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('programChart');
    if (ctx) {
      const labels = <?= $chartLabels ?>;
      const data = <?= $chartData ?>;
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Approved Projects',
            data: data,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 1
              }
            }
          }
        }
      });
    }
  });
</script>