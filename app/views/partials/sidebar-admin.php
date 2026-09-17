<div class="sidebar">

  <div>

    <!-- SIDEBAR TOP IMAGE -->
    <div class="sidebar-top-image">
      <img src="/images/ChatGPT Image May 10, 2026, 11_17_02 AM.png" alt="Sidebar Banner">
    </div>

    <!-- NAVIGATION -->
    <div class="nav-section-title">MAIN MENU</div>

    <div class="nav-menu">

      <!-- DASHBOARD -->
      <a href="/admin/dashboard" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/dashboard') === 0 ? 'active' : '' ?>">
        <div class="nav-left">
          <i class="fas fa-chart-line"></i>
          <span>Dashboard</span>
        </div>
      </a>
      <a href="/admin/colleges" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/colleges') === 0 ? 'active' : '' ?>">
        <div class="nav-left">
          <i class="fas fa-university"></i>
          <span>Colleges</span>
        </div>
      </a>
      <!-- MANAGE USER -->
      <a href="/admin/accounts" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/accounts') === 0 ? 'active' : '' ?>">
        <div class="nav-left">
          <i class="fas fa-users"></i>
          <span>Manage User Accounts</span>
        </div>
      </a>

      <!-- SYSTEM SETTINGS (dropdown) -->
      <div class="nav-dropdown">
        <div class="nav-item" onclick="toggleDropdown('settingsMenu')">
          <div class="nav-left">
            <i class="fas fa-cogs"></i>
            <span>Manage System Settings</span>
          </div>
          <i class="fas fa-chevron-down dropdown-icon"></i>
        </div>

        <div class="dropdown-content" id="settingsMenu">
          <a href="/admin/evaluation" class="sub-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/evaluation') === 0 ? 'active' : '' ?>">
            <i class="fas fa-check-circle"></i> Evaluation Criteria
          </a>
          <a href="/admin/proposal" class="sub-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/proposal') === 0 ? 'active' : '' ?>">
            <i class="fas fa-file-alt"></i> Call for Proposals
          </a>
        </div>
      </div>

      <!-- POSTS (dropdown) -->
      <div class="nav-dropdown">
        <div class="nav-item" onclick="toggleDropdown('postsMenu')">
          <div class="nav-left">
            <i class="fas fa-newspaper"></i>
            <span>Manage Posts</span>
          </div>
          <i class="fas fa-chevron-down dropdown-icon"></i>
        </div>

        <div class="dropdown-content" id="postsMenu">
          <a href="/admin/banner" class="sub-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/banner') === 0 ? 'active' : '' ?>">
            <i class="fas fa-image"></i> Banner
          </a>
          <a href="/admin/news" class="sub-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/news') === 0 ? 'active' : '' ?>">
            <i class="fas fa-bullhorn"></i> Latest News
          </a>
          <a href="/admin/events" class="sub-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/events') === 0 ? 'active' : '' ?>">
            <i class="fas fa-calendar"></i> Upcoming Events
          </a>
          <a href="/admin/about" class="sub-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/about') === 0 ? 'active' : '' ?>">
            <i class="fas fa-circle-info"></i> About
          </a>
          <a href="/admin/officials" class="sub-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/officials') === 0 ? 'active' : '' ?>">
            <i class="fas fa-user-tie"></i> Organizational Structure
          </a>
          <a href="/admin/crest" class="sub-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/crest') === 0 ? 'active' : '' ?>">
            <i class="fas fa-building-columns"></i> CREST
          </a>
        </div>
      </div>

      <!-- PAPER SUBMISSION (dropdown) -->
      <div class="nav-dropdown">
        <div class="nav-item" onclick="toggleDropdown('paperMenu')">
          <div class="nav-left">
            <i class="fas fa-folder-open"></i>
            <span>Manage Paper Submission</span>
          </div>
          <i class="fas fa-chevron-down dropdown-icon"></i>
        </div>

        <div class="dropdown-content" id="paperMenu">
          <a href="/admin/monitoring" class="sub-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/monitoring') === 0 ? 'active' : '' ?>">
            <i class="fas fa-eye"></i> Submission Monitoring
          </a>
        </div>
      </div>

    </div><!-- .nav-menu -->

  </div>

  <!-- PROFILE -->
  <div class="profile">
    <p id="username"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Gulliver') ?></p>
    <p id="role" style="text-transform: uppercase;"><?= htmlspecialchars($_SESSION['user_role'] ?? 'Director') ?></p>
    <button class="signout" onclick="openLogoutModal()">
      <i class="fas fa-sign-out-alt"></i> Sign Out
    </button>
  </div>

</div>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="modal" style="display:none;">
  <div class="modal-content" style="max-width:400px;">
    <div class="modal-header">
      <h3><i class="fas fa-sign-out-alt"></i> Confirm Logout</h3>
      <button class="close-modal" onclick="closeLogoutModal()">&times;</button>
    </div>
    <p style="margin:20px 0;">Are you sure you want to sign out?</p>
    <div style="display:flex; gap:10px; justify-content:flex-end;">
      <button class="btn btn-secondary" onclick="closeLogoutModal()">Cancel</button>
      <a href="/logout" class="btn btn-danger" style="text-decoration:none; color:#fff; padding:10px 20px; border-radius:6px; background:#dc3545;">Yes, Sign Out</a>
    </div>
  </div>
</div>

<script>
  function openLogoutModal() {
    document.getElementById('logoutModal').style.display = 'flex';
  }

  function closeLogoutModal() {
    document.getElementById('logoutModal').style.display = 'none';
  }

  // Close modal when clicking outside
  document.addEventListener('click', function(e) {
    const modal = document.getElementById('logoutModal');
    if (e.target === modal) {
      modal.style.display = 'none';
    }
  });
</script>