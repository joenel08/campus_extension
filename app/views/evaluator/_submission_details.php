<?php
$report_type = $report_type ?? ($submission['report_type'] ?? 'proposal');

// For proposal, use $form_data; for progress/terminal, use $parentFormData
$proposalData = ($report_type === 'proposal')
    ? ($form_data ?? [])
    : ($parentFormData ?? []);

$basic   = $proposalData['basic_info'] ?? [];
$components = $proposalData['components'] ?? [];

$progress = $form_data['progress_info'] ?? [];
$terminal = $form_data['terminal_info'] ?? [];

$file_path = $submission['attachment'] ?? $submission['file_path'] ?? $form_data['attachment'] ?? null;
?>

<div style="background:#f8fafc; padding:20px; border-radius:8px; border:1px solid #e5e7eb;">

    <!-- ================= PARENT PROPOSAL INFO ================= -->
    <h3 style="color:#183153; border-bottom:2px solid #e5e7eb; padding-bottom:10px;">
        <i class="fas fa-file-alt"></i> Proposal Information
    </h3>

    <h4 style="margin:15px 0 8px; color:#2563eb;">Basic Information</h4>
    <p><strong>Project Title:</strong> <?= htmlspecialchars($basic['project_title'] ?? 'N/A') ?></p>
    <p><strong>Project Leader:</strong> <?= htmlspecialchars($basic['project_leader'] ?? 'N/A') ?></p>
    <p><strong>Implementing Campus:</strong> <?= htmlspecialchars($basic['implementing_campus'] ?? 'N/A') ?></p>
    <p><strong>Lead Unit:</strong> <?= htmlspecialchars($basic['lead_unit'] ?? 'N/A') ?></p>
    <p><strong>Cooperating Unit:</strong> <?= htmlspecialchars($basic['cooperating_unit'] ?? 'N/A') ?></p>
    <p><strong>Project Site:</strong> <?= htmlspecialchars($basic['project_site'] ?? 'N/A') ?></p>
    <p><strong>Cooperating Agencies:</strong> <?= nl2br(htmlspecialchars($basic['cooperating_agencies'] ?? 'N/A')) ?></p>
    <p><strong>Beneficiaries:</strong> <?= htmlspecialchars($basic['beneficiaries'] ?? 'N/A') ?></p>
    <p><strong>Funding Agency:</strong> <?= htmlspecialchars($basic['funding_agency'] ?? 'N/A') ?></p>
    <p><strong>Budget:</strong> ₱<?= number_format($basic['budget'] ?? 0, 2) ?></p>

    <?php if (!empty($components)): ?>
        <h4 style="margin:15px 0 8px; color:#2563eb;">Components</h4>
        <ul>
            <?php foreach ($components as $comp): ?>
                <li><strong><?= htmlspecialchars($comp['title']) ?></strong> – <?= htmlspecialchars($comp['leader']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- ================= REPORT-SPECIFIC DETAILS ================= -->
   <?php if ($report_type === 'progress'): ?>
    <h3 style="color:#16a34a; border-top:2px solid #e5e7eb; padding-top:15px; margin-top:20px;">
        <i class="fas fa-chart-line"></i> Progress Report
    </h3>
    <p><strong>Reporting Date:</strong> <?= htmlspecialchars($progress['report_date'] ?? $submission['report_date'] ?? 'N/A') ?></p>
    <!-- Removed: Accomplishments, Issues, Next Plan -->

   <?php elseif ($report_type === 'terminal'): ?>
    <h3 style="color:#16a34a; border-top:2px solid #e5e7eb; padding-top:15px; margin-top:20px;">
        <i class="fas fa-check-circle"></i> Terminal Report
    </h3>
    <p><strong>Completion Date:</strong> <?= htmlspecialchars($terminal['completion_date'] ?? $submission['completion_date'] ?? 'N/A') ?></p>
    <!-- Removed: Overall Status, Final Summary, Lessons Learned, Recommendations -->
  <?php endif; ?>

    <!-- ================= ATTACHMENT ================= -->
    <?php
    // For progress/terminal, the attachment is on the report itself, not the parent
    $reportFile = null;
    if ($report_type === 'progress') {
        $reportFile = $submission['attachment'] ?? null;
    } elseif ($report_type === 'terminal') {
        $reportFile = $submission['attachment'] ?? null;
    } else {
        // proposal attachment from form_data
        $reportFile = $form_data['attachment'] ?? null;
    }
    ?>

    <?php if (!empty($reportFile) && file_exists($reportFile)): ?>
        <div style="margin-top:20px; padding:12px; background:#e8f0fe; border-radius:6px;">
            <i class="fas fa-paperclip"></i>
            <a href="/<?= htmlspecialchars($reportFile) ?>" target="_blank" style="font-weight:600; color:#2563eb;">
                Download Report Attachment
            </a>
        </div>
    <?php endif; ?>

    <!-- Parent proposal's attachment (optional) -->
    <?php if ($report_type !== 'proposal' && !empty($form_data['attachment'])): ?>
        <div style="margin-top:10px; padding:12px; background:#f0fdf4; border-radius:6px;">
            <i class="fas fa-paperclip"></i>
            <a href="/<?= htmlspecialchars($form_data['attachment']) ?>" target="_blank" style="font-weight:600; color:#16a34a;">
                Download Original Proposal Attachment
            </a>
        </div>
    <?php endif; ?>

</div>