<?php
$report_type = $report_type ?? 'proposal';

// For proposal, use $form_data; for progress/terminal, use $parentFormData
$proposalData = ($report_type === 'proposal')
    ? ($form_data ?? [])
    : ($parentFormData ?? []);

$basic = $proposalData['basic_info'] ?? [];
// $budget = $proposalData['budget_breakdown'] ?? [];
$components = $proposalData['components'] ?? [];

$file_path = $submission['attachment'] ?? $submission['file_path'] ?? $form_data['attachment'] ?? null;
?>

<style>
/* Hide print header on screen */
.print-header {
    display: none;
}

@media print {

    /* Hide UI chrome */
    .sidebar,
    .sidebar-top,
    .sidebar-nav,
    .sidebar-bottom,
    .topbar,
    .notification-wrapper,
    .notification-panel,
    .btn,
    .alert,
    .modal,
    .print-btn,
    a[href="/admin/monitoring"] {
        display: none !important;
    }

    /* Reset layout */
    body {
        background: #fff !important;
        display: block !important;
    }

    .main {
        margin-left: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }

    .card {
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
        border-radius: 0 !important;
    }

    /* Show print header */
    .print-header {
        display: block !important;
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #000;
    }

    .print-header h1 {
        font-size: 18px;
        margin: 0 0 5px;
        color: #000;
    }

    .print-header p {
        font-size: 12px;
        color: #333;
        margin: 2px 0;
    }

    /* Two-column layout stacks on print */
    .detail-layout {
        display: block !important;
    }

    .detail-layout > div {
        flex: none !important;
        width: 100% !important;
        min-width: 0 !important;
        margin-bottom: 20px;
    }

    /* Tables */
    table {
        width: 100% !important;
        border-collapse: collapse !important;
        font-size: 12px !important;
        page-break-inside: auto;
    }

    th, td {
        border: 1px solid #000 !important;
        padding: 6px !important;
        color: #000 !important;
    }

    th {
        background: #f0f0f0 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    /* Badges keep colors */
    .badge {
        border: 1px solid #000;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    /* Avoid page breaks inside evaluator cards */
    .evaluator-card {
        page-break-inside: avoid;
    }

    /* Attachment link: hide download UI, show filename only */
    .attachment-box {
        border: 1px solid #000 !important;
        padding: 6px !important;
    }

    /* Page setup */
    @page {
        size: A4 portrait;
        margin: 15mm;
    }
}
</style>
<div class="card" style="padding:30px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; flex-wrap:wrap; gap:10px;">
        <div>
            <h2 style="color:#183153;">
                <i class="fas fa-file-alt"></i>
                <?= $report_type === 'proposal' ? 'Proposal Details' : ucfirst($report_type) . ' Report Details' ?>
            </h2>
            <p style="color:#6b7280;">
                <?= date('F d, Y', strtotime($submission['created_at'])) ?>
                <?php if ($submission['proposal_id']): ?>
                    | Proposal ID: #<?= $submission['proposal_id'] ?>
                <?php endif; ?>
            </p>
        </div>
        <div>
    <span class="badge <?= $submission['status'] === 'approved' ? 'badge-approved' : ($submission['status'] === 'revision' ? 'badge-pending' : 'badge-declined') ?>" style="font-size:16px; padding:8px 16px;">
        <?= ucfirst($submission['status']) ?>
    </span>
    <button class="btn btn-primary print-btn" onclick="window.print()" style="margin-left:10px;">
        <i class="fas fa-print"></i> Print
    </button>
    <a href="/admin/monitoring" class="btn btn-secondary" style="margin-left:10px;">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>
    </div>
<div class="print-header">
    <h1>Isabela State University — Extension & Training Services</h1>
    <p>
        <?= $report_type === 'proposal' ? 'Proposal' : ucfirst($report_type) . ' Report' ?> Details
    </p>
    <p>Generated: <?= date('F d, Y h:i A') ?></p>
</div>
    <div class="detail-layout" style="display:flex; gap:30px; flex-wrap:wrap;">
        
        <!-- LEFT COLUMN: Submission Data -->
        <div style="flex:1.2; min-width:300px; background:#f8fafc; padding:20px; border-radius:8px; border:1px solid #e5e7eb;">
            <h3 style="color:#183153; border-bottom:2px solid #e5e7eb; padding-bottom:10px;">
                <i class="fas fa-file-alt"></i> Submission Data
            </h3>

            <!-- === PARENT PROPOSAL INFO (always shown) === -->
            <h4 style="margin:15px 0 8px; color:#2563eb;">Proposal Information</h4>
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
                        <li><strong><?= htmlspecialchars($comp['title'] ?? '') ?></strong> – <?= htmlspecialchars($comp['leader'] ?? '') ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <!-- === PROPOSAL-ONLY: Budget Breakdown === -->
            <?php if ($report_type === 'proposal'): ?>
                <!-- <h4 style="margin:15px 0 8px; color:#2563eb;">Budget Breakdown</h4>
                <table style="width:100%; border-collapse:collapse; font-size:14px;">
                    <tr><th style="text-align:left;">Year</th><th style="text-align:right;">PS</th><th style="text-align:right;">MOOE</th><th style="text-align:right;">CO</th></tr>
                    <tr><td>Year 1</td><td style="text-align:right;"><?= number_format($budget['year1_ps'] ?? 0, 2) ?></td><td style="text-align:right;"><?= number_format($budget['year1_mooe'] ?? 0, 2) ?></td><td style="text-align:right;"><?= number_format($budget['year1_co'] ?? 0, 2) ?></td></tr>
                    <tr><td>Year 2</td><td style="text-align:right;"><?= number_format($budget['year2_ps'] ?? 0, 2) ?></td><td style="text-align:right;"><?= number_format($budget['year2_mooe'] ?? 0, 2) ?></td><td style="text-align:right;"><?= number_format($budget['year2_co'] ?? 0, 2) ?></td></tr>
                    <tr><td>Year 3</td><td style="text-align:right;"><?= number_format($budget['year3_ps'] ?? 0, 2) ?></td><td style="text-align:right;"><?= number_format($budget['year3_mooe'] ?? 0, 2) ?></td><td style="text-align:right;"><?= number_format($budget['year3_co'] ?? 0, 2) ?></td></tr>
                </table> -->
            <?php endif; ?>

            <!-- === PROGRESS REPORT DATA === -->
            <?php if ($report_type === 'progress'): ?>
                <h4 style="margin:20px 0 8px; color:#16a34a; border-top:1px solid #e5e7eb; padding-top:15px;">Progress Report Details</h4>
                <p><strong>Report Date:</strong> <?= htmlspecialchars($submission['report_date'] ?? 'N/A') ?></p>
                <!-- <p><strong>Accomplishments:</strong> <?= nl2br(htmlspecialchars($submission['accomplishments'] ?? 'N/A')) ?></p>
                <p><strong>Issues:</strong> <?= nl2br(htmlspecialchars($submission['issues'] ?? 'N/A')) ?></p>
                <p><strong>Next Plan:</strong> <?= nl2br(htmlspecialchars($submission['next_plan'] ?? 'N/A')) ?></p> -->
            <?php endif; ?>

            <!-- === TERMINAL REPORT DATA === -->
            <?php if ($report_type === 'terminal'): ?>
                <h4 style="margin:20px 0 8px; color:#16a34a; border-top:1px solid #e5e7eb; padding-top:15px;">Terminal Report Details</h4>
                <p><strong>Completion Date:</strong> <?= htmlspecialchars($submission['completion_date'] ?? 'N/A') ?></p>
                <!-- <p><strong>Overall Status:</strong> <?= htmlspecialchars($submission['overall_status'] ?? 'N/A') ?></p>
                <p><strong>Final Summary:</strong> <?= nl2br(htmlspecialchars($submission['final_summary'] ?? 'N/A')) ?></p>
                <p><strong>Lessons Learned:</strong> <?= nl2br(htmlspecialchars($submission['lessons_learned'] ?? 'N/A')) ?></p>
                <p><strong>Recommendations:</strong> <?= nl2br(htmlspecialchars($submission['recommendations'] ?? 'N/A')) ?></p> -->
            <?php endif; ?>

            <!-- === ATTACHMENT === -->
            <?php if (!empty($file_path)): ?>
                <div class="attachment-box" style="margin-top:20px; padding:12px; background:#e8f0fe; border-radius:6px;">
                    <i class="fas fa-paperclip"></i>
                    <a href="/<?= htmlspecialchars($file_path) ?>" target="_blank" style="font-weight:600; color:#2563eb;">
                        Download Attachment
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- RIGHT COLUMN: Evaluator Feedback -->
        <div style="flex:1; min-width:300px;">
            <h3 style="color:#183153; border-bottom:2px solid #e5e7eb; padding-bottom:10px;">
                <i class="fas fa-users"></i> Evaluator Feedback
            </h3>

            <?php if (empty($votes)): ?>
                <p style="color:#999;">No evaluator feedback yet.</p>
            <?php else: ?>
                <?php foreach ($votes as $v): ?>
                   <div class="evaluator-card" style="background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:15px; margin-bottom:15px;">    <div style="display:flex; justify-content:space-between; align-items:center;">
                            <strong><?= htmlspecialchars($v['evaluator_name']) ?></strong>
                            <span class="badge <?= $v['vote'] === 'approve' ? 'badge-approved' : ($v['vote'] === 'revision' ? 'badge-pending' : 'badge-declined') ?>">
                                <?= ucfirst($v['vote']) ?>
                            </span>
                        </div>
                        <p style="margin-top:5px; font-size:14px; color:#555;">
                            <strong>Comments:</strong> <?= nl2br(htmlspecialchars($v['comments'] ?? 'N/A')) ?>
                        </p>
                        <p style="font-size:12px; color:#999;">Submitted: <?= date('M d, Y H:i', strtotime($v['submitted_at'])) ?></p>

                        <?php if ($report_type === 'proposal' && !empty($groupedRatings)): ?>
                            <?php if (isset($groupedRatings[$v['evaluator_id']])): ?>
                                <div style="margin-top:10px; padding:10px; background:#f8fafc; border-radius:6px;">
                                    <h5 style="font-size:14px; margin-bottom:5px;">Ratings</h5>
                                    <?php foreach ($groupedRatings[$v['evaluator_id']]['criteria'] as $crit): ?>
                                        <div style="display:flex; justify-content:space-between; font-size:13px; border-bottom:1px solid #eee; padding:3px 0;">
                                            <span><?= htmlspecialchars($crit['criteria_text']) ?></span>
                                            <strong><?= $crit['rating'] ?>/5</strong>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>