<?php
$basic = $form_data['basic_info'] ?? [];
$budget = $form_data['budget_breakdown'] ?? [];
$components = $form_data['components'] ?? [];
$remarks = $form_data['admin_remarks'] ?? '';
?>

<div class="submission-card" style="padding:0;">

    <!-- Header with Status and Actions -->
    <div style="display:flex; justify-content:space-between; align-items:center; padding:22px; border-bottom:1px solid #dfe5ec; flex-wrap:wrap; gap:10px;">
        <div>
            <h2 style="color:#183153;"><i class="fas fa-file-alt"></i> Submission Details</h2>
            <p style="color:#6b7280;">
                <?= ucfirst($submission['report_type']) ?> – 
                <?= date('F d, Y', strtotime($submission['created_at'])) ?>
                <?php if ($submission['proposal_id']): ?>
                    | Proposal ID: #<?= $submission['proposal_id'] ?>
                <?php endif; ?>
            </p>
        </div>
        <div style="display:flex; align-items:center; gap:15px; flex-wrap:wrap;">
            <span class="badge <?= $submission['status'] === 'submitted' ? 'badge-approved' : ($submission['status'] === 'draft' ? 'badge-pending' : 'badge-declined') ?>" style="font-size:16px; padding:8px 16px;">
                <?= ucfirst($submission['status']) ?>
            </span>
            <?php if ($remarks): ?>
                <span style="color:#dc3545; font-size:14px; background:#f8d7da; padding:4px 12px; border-radius:20px;">
                    <i class="fas fa-comment"></i> Has Remarks
                </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Content Grid (same as _form.php) -->
    <div class="submission-grid">

        <!-- SECTION A: BASIC INFORMATION -->
        <div class="submission-group full" style="background:#f8fafc; font-weight:700; font-size:18px; padding:15px 22px; border-bottom:1px solid #dfe5ec;">
            <i class="fas fa-info-circle"></i> A. BASIC INFORMATION
        </div>

        <div class="submission-group full">
            <div class="submission-label">1. Program/Project Title</div>
            <div class="submission-value"><?= htmlspecialchars($basic['project_title'] ?? 'N/A') ?></div>
        </div>

        <div class="submission-group full">
            <div class="submission-label">Project Leader</div>
            <div class="submission-value"><?= htmlspecialchars($basic['project_leader'] ?? 'N/A') ?></div>
        </div>

        <!-- Components Section (replaces static component fields) -->
        <div class="submission-group full">
            <div class="submission-label">Project Components</div>
            <div class="submission-value">
                <?php if (!empty($components)): ?>
                    <table style="width:100%; border-collapse:collapse; margin-top:8px;">
                        <thead>
                            <tr style="background:#f8fafc;">
                                <th style="padding:8px; border:1px solid #dfe5ec; text-align:left;">Component Title</th>
                                <th style="padding:8px; border:1px solid #dfe5ec; text-align:left;">Component Leader</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($components as $comp): ?>
                                <tr>
                                    <td style="padding:8px; border:1px solid #dfe5ec;"><?= htmlspecialchars($comp['title'] ?? '') ?></td>
                                    <td style="padding:8px; border:1px solid #dfe5ec;"><?= htmlspecialchars($comp['leader'] ?? '') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    No components specified.
                <?php endif; ?>
            </div>
        </div>

        <div class="submission-group full">
            <div class="submission-label">3. Implementing Campus</div>
            <div class="submission-value"><?= htmlspecialchars($basic['implementing_campus'] ?? 'N/A') ?></div>
        </div>

        <div class="submission-group">
            <div class="submission-label">a. Lead Unit/College</div>
            <div class="submission-value"><?= htmlspecialchars($basic['lead_unit'] ?? 'N/A') ?></div>
        </div>

        <div class="submission-group">
            <div class="submission-label">b. Cooperating Unit/College</div>
            <div class="submission-value"><?= htmlspecialchars($basic['cooperating_unit'] ?? 'N/A') ?></div>
        </div>

        <div class="submission-group full">
            <div class="submission-label">c. Project Site</div>
            <div class="submission-value"><?= htmlspecialchars($basic['project_site'] ?? 'N/A') ?></div>
        </div>

        <div class="submission-group full">
            <div class="submission-label">4. Cooperating Agencies</div>
            <div class="submission-value" style="white-space:pre-wrap;"><?= nl2br(htmlspecialchars($basic['cooperating_agencies'] ?? 'N/A')) ?></div>
        </div>

        <div class="submission-group">
            <div class="submission-label">5. Date Started</div>
            <div class="submission-value"><?= htmlspecialchars($basic['date_started'] ?? 'N/A') ?></div>
        </div>

        <div class="submission-group">
            <div class="submission-label">Date Completed</div>
            <div class="submission-value"><?= htmlspecialchars($basic['date_completed'] ?? 'N/A') ?></div>
        </div>

        <div class="submission-group full">
            <div class="submission-label">Status</div>
            <div class="submission-value"><?= htmlspecialchars($basic['status'] ?? 'N/A') ?></div>
        </div>

        <div class="submission-group full">
            <div class="submission-label">6. Project Beneficiaries</div>
            <div class="submission-value"><?= htmlspecialchars($basic['beneficiaries'] ?? 'N/A') ?></div>
        </div>

        <div class="submission-group">
            <div class="submission-label">7. Funding Agency/ies</div>
            <div class="submission-value"><?= htmlspecialchars($basic['funding_agency'] ?? 'N/A') ?></div>
        </div>

        <div class="submission-group">
            <div class="submission-label">Budget</div>
            <div class="submission-value"><?= number_format($basic['budget'] ?? 0, 2) ?></div>
        </div>

        <!-- BUDGET BREAKDOWN TABLE -->
        <div class="submission-group full" style="background:#f8fafc; font-weight:700; font-size:16px; padding:15px 22px; border-top:1px solid #dfe5ec;">
            <i class="fas fa-table"></i> 8. Budget Requirement / Budget
        </div>

        <div class="submission-group full" style="padding:0; border:none;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:12px; border:1px solid #dfe5ec;">Fund Year</th>
                        <th style="padding:12px; border:1px solid #dfe5ec;">PS</th>
                        <th style="padding:12px; border:1px solid #dfe5ec;">MOOE</th>
                        <th style="padding:12px; border:1px solid #dfe5ec;">CO</th>
                        <th style="padding:12px; border:1px solid #dfe5ec;">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $years = [
                        'Year 1' => ['ps' => $budget['year1_ps'] ?? 0, 'mooe' => $budget['year1_mooe'] ?? 0, 'co' => $budget['year1_co'] ?? 0],
                        'Year 2' => ['ps' => $budget['year2_ps'] ?? 0, 'mooe' => $budget['year2_mooe'] ?? 0, 'co' => $budget['year2_co'] ?? 0],
                        'Year 3' => ['ps' => $budget['year3_ps'] ?? 0, 'mooe' => $budget['year3_mooe'] ?? 0, 'co' => $budget['year3_co'] ?? 0],
                    ];
                    foreach ($years as $label => $vals):
                        $total = $vals['ps'] + $vals['mooe'] + $vals['co'];
                    ?>
                        <tr>
                            <td style="padding:12px; border:1px solid #dfe5ec; font-weight:600;"><?= $label ?></td>
                            <td style="padding:12px; border:1px solid #dfe5ec; text-align:right;"><?= number_format($vals['ps'], 2) ?></td>
                            <td style="padding:12px; border:1px solid #dfe5ec; text-align:right;"><?= number_format($vals['mooe'], 2) ?></td>
                            <td style="padding:12px; border:1px solid #dfe5ec; text-align:right;"><?= number_format($vals['co'], 2) ?></td>
                            <td style="padding:12px; border:1px solid #dfe5ec; font-weight:600; text-align:right;"><?= number_format($total, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Admin Remarks -->
        <?php if ($remarks): ?>
            <div class="submission-group full" style="background:#fff3cd; border-left:4px solid #ffc107;">
                <div class="submission-label" style="color:#856404;">Admin Remarks</div>
                <div class="submission-value" style="color:#856404;"><?= nl2br(htmlspecialchars($remarks)) ?></div>
            </div>
        <?php endif; ?>

    </div>

    <!-- Actions -->
    <div style="display:flex; gap:15px; flex-wrap:wrap; padding:22px; border-top:1px solid #dfe5ec; background:#f8fafc;">
        <a href="/extensionist/submissions" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to List</a>
        <?php if ($submission['status'] === 'draft'): ?>
            <a href="/extensionist/submissions/edit?id=<?= $submission['id'] ?>" class="btn btn-edit"><i class="fas fa-edit"></i> Edit</a>
        <?php endif; ?>
    </div>

</div>

<style>
/* Reuse submission-grid styles from the form */
.submission-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
}
.submission-group {
    padding: 16px 22px;
    border-right: 1px solid #dfe5ec;
    border-bottom: 1px solid #dfe5ec;
}
.submission-group:nth-child(2n) {
    border-right: none;
}
.submission-group.full {
    grid-column: 1 / span 2;
    border-right: none;
}
.submission-label {
    font-weight: 600;
    font-size: 14px;
    color: #4b5563;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.submission-value {
    font-size: 15px;
    color: #111827;
    padding: 6px 0;
    min-height: 38px;
    border-bottom: 1px dashed #e5e7eb;
}
/* Adjust table styles */
.submission-value table {
    font-size: 14px;
}
</style>