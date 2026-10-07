<!-- HERO -->
<!-- HERO -->
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
        <div class="hero-badge">Extensionist Panel</div>
        <h1 class="hero-name">Welcome, <?= htmlspecialchars($currentUser['name'] ?? 'Admin') ?>!</h1>
        <p class="hero-role">Extension Services Management System</p>
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
    <div class="hero-date"><i class="fas fa-calendar"></i> Academic Year: <?= htmlspecialchars($_SESSION['academic_year_label'] ?? 'Academic Year') ?></div>
    <div>
      <button onclick="openProfileModal()" style="padding:10px 18px; border:none; border-radius:8px; background:#fff; color:#183153; font-weight:600; cursor:pointer;">
        <i class="fas fa-user-edit"></i> Edit Profile
      </button>
    </div>
  </div>
</div>
<!-- STATS -->
<div class="stats-grid">
  <div class="stat-card" onclick="window.location.href='/extensionist/submissions'">
    <div class="stat-icon"><i class="fas fa-file"></i></div>
    <div class="stat-number"><?= $total ?? 0 ?></div>
    <div class="stat-title">My Projects</div>
  </div>
  <div class="stat-card" onclick="window.location.href='/extensionist/submissions/filtered?filter=pending'">
    <div class="stat-icon"><i class="fas fa-spinner"></i></div>
    <div class="stat-number"><?= $pending ?? 0 ?></div>
    <div class="stat-title">Pending</div>
  </div>
  <div class="stat-card" onclick="window.location.href='/extensionist/submissions/filtered?filter=completed'">
    <div class="stat-icon"><i class="fas fa-circle-check"></i></div>
    <div class="stat-number"><?= $completed ?? 0 ?></div>
    <div class="stat-title">Completed</div>
  </div>
</div>
<!-- CALL FOR SUBMISSION -->
<div class="call-submission-card">
  <div class="call-submission-header">
    <div>
      <h2><i class="fas fa-bullhorn"></i> Call for Submission</h2>
      <p>Submit your extension proposals, terminal reports, and related documents for review and approval.</p>
    </div>
    <button class="submit-btn" onclick="window.location.href='/extensionist/submissions'">
      Submit Now <span style="font-weight:700;">!!!</span>
    </button>
  </div>
  <div class="submission-grid">
    <div class="submission-box full">
      <i class="fas fa-file-alt"></i>
      <h4>Extension Proposal</h4>
      <p>Upload approved proposal documents and activity plans.</p>
      <!-- Open Proposals -->
      <?php if (!empty($proposals)): ?>
        <div class="call-submission-card">
          <div class="call-submission-header">
            <div>
              <h2><i class="fas fa-bullhorn"></i> Open Calls for Proposal</h2>
              <p>Submit your extension proposals for the following calls</p>
            </div>
          </div>
          <div class="submission-grid">
            <?php foreach ($proposals as $proposal): ?>
              <div class="submission-box">
                <i class="fas fa-file-alt"></i>
                <h4><?= htmlspecialchars($proposal['title']) ?></h4>
                <p><?= htmlspecialchars($proposal['description']) ?></p>

                <p>Deadline: <?= date('M d, Y', strtotime($proposal['closing_date'])) ?></p>
                <a href="/extensionist/submissions/create?proposal_id=<?= $proposal['id'] ?>&type=proposal" class="submit-btn" style="display:inline-block; margin-top:10px;">
                  Submit Now
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php else: ?>
        <p style="text-align:center; color:#777; margin:20px 0;">No open calls for your college at the moment.</p>
      <?php endif; ?>
    </div>
    <!-- <div class="submission-box">
      <i class="fas fa-chart-line"></i>
      <h4>Progress Report</h4>
      <p>Submit accomplishment and monitoring reports.</p>
    </div>
    <div class="submission-box">
      <i class="fas fa-folder-open"></i>
      <h4>Terminal Report</h4>
      <p>Provide final documentation and evaluation results.</p>
    </div> -->
  </div>
</div>

<!-- PROFILE MODAL -->
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

    <form method="POST" action="/extensionist/profile/update" enctype="multipart/form-data">
      <div style="margin-bottom:15px;">
        <label style="font-weight:600;">Full Name</label>
        <input type="text" name="name" required
          value="<?= htmlspecialchars($currentUser['name'] ?? '') ?>"
          style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
      </div>
      <div style="margin-bottom:15px;">
        <label style="font-weight:600;">Email Address</label>
        <input type="email" name="email" required
          value="<?= htmlspecialchars($currentUser['email'] ?? '') ?>"
          style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
      </div>
      <div style="margin-bottom:15px;">
        <label style="font-weight:600;">New Password (leave blank to keep current)</label>
        <input type="password" name="password"
          style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
      </div>
      <div style="margin-bottom:15px;">
        <label style="font-weight:600;">Profile Picture</label>
        <?php if (!empty($currentUser['profile_picture']) && file_exists($currentUser['profile_picture'])): ?>
          <div style="margin-bottom:8px;">
            <img src="/<?= htmlspecialchars($currentUser['profile_picture']) ?>"
              style="width:60px; height:60px; border-radius:50%; object-fit:cover;">
          </div>
        <?php endif; ?>
        <input type="file" name="profile_picture" accept="image/*" style="width:100%; padding:10px;">
      </div>
      <div style="display:flex; gap:10px;">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
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
    const m = document.getElementById('profileModal');
    if (e.target === m) m.style.display = 'none';
  });
</script>