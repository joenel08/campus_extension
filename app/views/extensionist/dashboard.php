<!-- HERO -->
<div class="hero-dashboard">
  <div class="hero-left">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Extensionist') ?></h1>
    <p>Extension Services Management System</p>
  </div>
  <div class="hero-right">
    <div><i class="fas fa-location-dot"></i> ISU Cabagan</div>
    <div><i class="fas fa-calendar"></i> Academic Year 2026</div>
  </div>
</div>

<!-- STATS -->
<div class="stats-grid">
  <div class="stat-card" onclick="window.location.href='/extensionist/submissions'">
    <div class="stat-icon"><i class="fas fa-file"></i></div>
    <div class="stat-number">1</div>
    <div class="stat-title">Submitted Papers</div>
  </div>
  <div class="stat-card" onclick="showPDFs('Pending Papers')">
    <div class="stat-icon"><i class="fas fa-spinner"></i></div>
    <div class="stat-number">1</div>
    <div class="stat-title">Pending</div>
  </div>
  <div class="stat-card" onclick="showPDFs('Completed Papers')">
    <div class="stat-icon"><i class="fas fa-circle-check"></i></div>
    <div class="stat-number">0</div>
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
                <p>Deadline: <?= date('M d, Y', strtotime($proposal['closing_date'])) ?></p>
                <a href="/extensionist/submissions/create?proposal_id=<?= $proposal['id'] ?>" class="submit-btn" style="display:inline-block; margin-top:10px;">
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