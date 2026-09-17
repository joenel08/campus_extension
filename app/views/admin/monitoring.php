
<!-- HEADER -->
<div class="monitor-header">
  <i class="fas fa-folder"></i>
  <div class="monitor-title">Submissions Monitoring</div>
</div>

<!-- FILTER BAR -->
<div class="filter-bar">

  <div class="filter-item">
    <select id="reportType">
      <option>-Select-</option>
      <option>Proposal</option>
      <option>Progress Report</option>
      <option>Terminal Report</option>
    </select>
  </div>

  <div class="filter-item">
    <select id="categoryType">
      <option>-Select Category-</option>
      <option>Internally Funded</option>
      <option>Externally Funded</option>
    </select>
  </div>

  <button class="filter-btn"
          onclick="filterReports()">
    <i class="fas fa-filter"></i>
    Filter Reports
  </button>

</div>

<!-- RESULT -->
<div id="monitorResult"
     style="margin-top:20px;"></div>