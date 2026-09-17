<div class="sidebar">

    <div>

        <!-- SIDEBAR TOP IMAGE -->
        <div class="sidebar-top-image">
            <img src="/images/ChatGPT Image May 10, 2026, 11_17_02 AM.png" alt="Sidebar Banner">
        </div>

        <!-- USER -->
        <div class="nav-section-title">MAIN MENU</div>
        <div class="nav-menu">
            <a href="/evaluator/dashboard" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/evaluator/dashboard') === 0 ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- PROFILE -->
    <div class="profile">
        <p id="username"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Evaluator') ?></p>
        <p id="role" style="text-transform: uppercase;"><?= htmlspecialchars($_SESSION['user_role'] ?? 'evaluator') ?></p>

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