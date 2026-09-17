<?php
$basic   = $form_data['basic_info'] ?? [];
$budget  = $form_data['budget_breakdown'] ?? [];
$components = $form_data['components'] ?? [];
$progress = $form_data['progress_info'] ?? [];
$terminal = $form_data['terminal_info'] ?? [];

// Use the $report_type passed from the controller, not $submission['report_type']
$report_type = $report_type ?? ($submission['report_type'] ?? 'proposal');

$file_path = $submission['attachment'] ?? $submission['file_path'] ?? $form_data['attachment'] ?? null;
?>

<div style="background:#f8fafc; padding:20px; border-radius:8px; border:1px solid #e5e7eb;">
    <h3 style="color:#183153; border-bottom:2px solid #e5e7eb; padding-bottom:10px;">
        <i class="fas fa-file-alt"></i> Submission Details
    </h3>

    <?php if ($report_type === 'proposal'): ?>
        <!-- Basic Info -->
        <h4 style="margin:15px 0 8px;">Basic Information</h4>
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
            <h4 style="margin:15px 0 8px;">Components</h4>
            <ul>
                <?php foreach ($components as $comp): ?>
                    <li><strong><?= htmlspecialchars($comp['title']) ?></strong> – <?= htmlspecialchars($comp['leader']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <h4 style="margin:15px 0 8px;">Budget Breakdown</h4>
        <table style="width:100%; border-collapse:collapse; font-size:14px;">
            <tr><th style="text-align:left;">Year</th><th style="text-align:right;">PS</th><th style="text-align:right;">MOOE</th><th style="text-align:right;">CO</th></tr>
            <tr><td>Year 1</td><td style="text-align:right;"><?= number_format($budget['year1_ps'] ?? 0, 2) ?></td><td style="text-align:right;"><?= number_format($budget['year1_mooe'] ?? 0, 2) ?></td><td style="text-align:right;"><?= number_format($budget['year1_co'] ?? 0, 2) ?></td></tr>
            <tr><td>Year 2</td><td style="text-align:right;"><?= number_format($budget['year2_ps'] ?? 0, 2) ?></td><td style="text-align:right;"><?= number_format($budget['year2_mooe'] ?? 0, 2) ?></td><td style="text-align:right;"><?= number_format($budget['year2_co'] ?? 0, 2) ?></td></tr>
            <tr><td>Year 3</td><td style="text-align:right;"><?= number_format($budget['year3_ps'] ?? 0, 2) ?></td><td style="text-align:right;"><?= number_format($budget['year3_mooe'] ?? 0, 2) ?></td><td style="text-align:right;"><?= number_format($budget['year3_co'] ?? 0, 2) ?></td></tr>
        </table>

    <?php elseif ($report_type === 'progress'): ?>
    <h4 style="margin:15px 0 8px;">Progress Report</h4>
    <p><strong>Reporting Date:</strong> <?= htmlspecialchars($progress['report_date'] ?? $submission['report_date'] ?? 'N/A') ?></p>
    <p><strong>Accomplishments:</strong> <?= nl2br(htmlspecialchars($progress['accomplishments'] ?? $submission['accomplishments'] ?? 'N/A')) ?></p>
    <p><strong>Issues / Challenges:</strong> <?= nl2br(htmlspecialchars($progress['issues'] ?? $submission['issues'] ?? 'N/A')) ?></p>
    <p><strong>Next Plan:</strong> <?= nl2br(htmlspecialchars($progress['next_plan'] ?? $submission['next_plan'] ?? 'N/A')) ?></p>

<?php elseif ($report_type === 'terminal'): ?>
    <h4 style="margin:15px 0 8px;">Terminal Report</h4>
    <p><strong>Completion Date:</strong> <?= htmlspecialchars($terminal['completion_date'] ?? $submission['completion_date'] ?? 'N/A') ?></p>
    <p><strong>Overall Status:</strong> <?= htmlspecialchars($terminal['overall_status'] ?? $submission['overall_status'] ?? 'N/A') ?></p>
    <p><strong>Final Summary:</strong> <?= nl2br(htmlspecialchars($terminal['final_summary'] ?? $submission['final_summary'] ?? 'N/A')) ?></p>
    <p><strong>Lessons Learned:</strong> <?= nl2br(htmlspecialchars($terminal['lessons_learned'] ?? $submission['lessons_learned'] ?? 'N/A')) ?></p>
    <p><strong>Recommendations:</strong> <?= nl2br(htmlspecialchars($terminal['recommendations'] ?? $submission['recommendations'] ?? 'N/A')) ?></p>
<?php endif; ?>

    <!-- File download -->
    <?php if ($file_path && file_exists($file_path)): ?>
        <div style="margin-top:20px; padding:12px; background:#e8f0fe; border-radius:6px;">
            <i class="fas fa-paperclip"></i>
            <a href="/<?= $file_path ?>" target="_blank" style="font-weight:600; color:#2563eb;">Download Attachment</a>
        </div>
    <?php endif; ?>
</div>