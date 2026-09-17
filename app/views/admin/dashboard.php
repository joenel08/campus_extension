<!-- WELCOME SECTION -->
<div class="hero-dashboard">

  <div class="hero-left">

    <div class="hero-profile">

      <div class="hero-image">
        <img src="https://i.pravatar.cc/200?img=12">
      </div>

      <div>

        <div class="hero-badge">
          Admin Panel
        </div>

        <h1 class="hero-name">
          <?= htmlspecialchars($_SESSION['user_name'] ?? 'Director User') ?>
        </h1>

        <p class="hero-role">
          Research & Extension Director
        </p>

      </div>

    </div>

  </div>

  <div class="hero-right">

    <div class="hero-campus">
      <i class="fas fa-location-dot"></i>
      ISU Cabagan Campus
    </div>

    <div class="hero-date">
      <i class="fas fa-calendar"></i>
      Academic Year 2026
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
    <div class="modern-title">Total Submissions</div>
    <div class="modern-progress">
      <div class="progress-bar progress-green"></div>
    </div>
  </div>

  <!-- ONGOING -->
  <div class="modern-card blue-card"
    onclick="window.location.href='/admin/monitoring?status=ongoing'">
    <div class="modern-icon"><i class="fas fa-spinner"></i></div>
    <div class="modern-number"><?= $ongoing ?? 0 ?></div>
    <div class="modern-title">On-Going</div>
    <div class="modern-progress">
      <div class="progress-bar progress-blue"></div>
    </div>
  </div>

  <!-- COMPLETED -->
  <div class="modern-card orange-card"
    onclick="window.location.href='/admin/monitoring?status=completed'">
    <div class="modern-icon"><i class="fas fa-circle-check"></i></div>
    <div class="modern-number"><?= $completed ?? 0 ?></div>
    <div class="modern-title">Completed Papers</div>
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
              ticks: { stepSize: 1 }
            }
          }
        }
      });
    }
  });
</script>