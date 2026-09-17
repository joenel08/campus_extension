<?php
$basic = $form_data['basic_info'] ?? [];
$budget = $form_data['budget_breakdown'] ?? [];
$components = $form_data['components'] ?? [];
$progress = $form_data['progress_info'] ?? [];
$terminal = $form_data['terminal_info'] ?? [];
$file_path = $submission['file_path'] ?? $form_data['attachment'] ?? null;
$report_type = $submission['report_type'];
?>

<div class="card" style="padding:30px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; flex-wrap:wrap; gap:10px;">
        <div>
            <h2 style="color:#183153;"><i class="fas fa-file-alt"></i> Submission Details</h2>
            <p style="color:#6b7280;">
                <?= ucfirst($report_type) ?> – 
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
            <?php if ($form_data['admin_remarks'] ?? ''): ?>
                <span style="color:#6c757d; font-size:14px; margin-left:10px;">Remarks: <?= htmlspecialchars($form_data['admin_remarks']) ?></span>
            <?php endif; ?>
        </div>
    </div>

    <div style="display:flex; gap:30px; flex-wrap:wrap;">
        <!-- LEFT COLUMN: Submission Data -->
        <div style="flex:1.2; min-width:300px; background:#f8fafc; padding:20px; border-radius:8px; border:1px solid #e5e7eb;">
            <h3 style="color:#183153; border-bottom:2px solid #e5e7eb; padding-bottom:10px;">
                <i class="fas fa-file-alt"></i> Submission Data
            </h3>

            <?php if ($report_type === 'proposal'): ?>
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
                <p><strong>Reporting Date:</strong> <?= htmlspecialchars($progress['report_date'] ?? 'N/A') ?></p>
                <p><strong>Accomplishments:</strong> <?= nl2br(htmlspecialchars($progress['accomplishments'] ?? 'N/A')) ?></p>
                <p><strong>Issues / Challenges:</strong> <?= nl2br(htmlspecialchars($progress['issues'] ?? 'N/A')) ?></p>
                <p><strong>Next Plan:</strong> <?= nl2br(htmlspecialchars($progress['next_plan'] ?? 'N/A')) ?></p>

            <?php elseif ($report_type === 'terminal'): ?>
                <h4 style="margin:15px 0 8px;">Terminal Report</h4>
                <p><strong>Completion Date:</strong> <?= htmlspecialchars($terminal['completion_date'] ?? 'N/A') ?></p>
                <p><strong>Overall Status:</strong> <?= htmlspecialchars($terminal['overall_status'] ?? 'N/A') ?></p>
                <p><strong>Final Summary:</strong> <?= nl2br(htmlspecialchars($terminal['final_summary'] ?? 'N/A')) ?></p>
                <p><strong>Lessons Learned:</strong> <?= nl2br(htmlspecialchars($terminal['lessons_learned'] ?? 'N/A')) ?></p>
                <p><strong>Recommendations:</strong> <?= nl2br(htmlspecialchars($terminal['recommendations'] ?? 'N/A')) ?></p>
            <?php endif; ?>

            <!-- File download -->
            <?php if ($file_path && file_exists($file_path)): ?>
                <div style="margin-top:20px; padding:12px; background:#e8f0fe; border-radius:6px;">
                    <i class="fas fa-paperclip"></i>
                    <a href="/<?= $file_path ?>" target="_blank" style="font-weight:600; color:#2563eb;">Download Attachment</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- RIGHT COLUMN: Evaluator Votes & Ratings -->
        <div style="flex:1; min-width:300px;">
            <h3 style="color:#183153; border-bottom:2px solid #e5e7eb; padding-bottom:10px;">
                <i class="fas fa-users"></i> Evaluator Feedback
            </h3>

            <?php if (empty($votes)): ?>
                <p style="color:#999;">No evaluator feedback yet.</p>
            <?php else: ?>
                <?php foreach ($votes as $v): ?>
                    <div style="background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:15px; margin-bottom:15px;">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
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

    <div style="margin-top:25px; display:flex; gap:10px; flex-wrap:wrap;">
        <a href="/admin/monitoring" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
        <?php if ($submission['status'] === 'submitted'): ?>
            <!-- Add action buttons for admin if needed -->
            <button class="btn btn-success" onclick="openActionModal(<?= $submission['id'] ?>, 'approve')"><i class="fas fa-check"></i> Approve</button>
            <button class="btn btn-warning" onclick="openActionModal(<?= $submission['id'] ?>, 'revise')"><i class="fas fa-edit"></i> Request Revision</button>
            <button class="btn btn-danger" onclick="openActionModal(<?= $submission['id'] ?>, 'decline')"><i class="fas fa-times"></i> Decline</button>
        <?php endif; ?>
    </div>
</div>

<!-- Action Modal (reuse the existing one) -->
<!-- Remarks Modal for Revision/Decline -->
<div id="remarksModal" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:500px;">
        <div class="modal-header">
            <h3 id="remarksModalTitle">Action</h3>
            <button class="close-modal" onclick="closeRemarksModal()">&times;</button>
        </div>
        <form method="POST" id="remarksForm">
            <input type="hidden" name="id" id="remarksId">
            <div style="margin-bottom:15px;">
                <label>Remarks</label>
                <textarea name="remarks" class="form-input" rows="4" placeholder="Provide feedback..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary" id="remarksSubmitBtn">Confirm</button>
            <button type="button" class="btn btn-secondary" onclick="closeRemarksModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
    function openRemarksModal(id, action) {
        document.getElementById('remarksId').value = id;
        const form = document.getElementById('remarksForm');
        let actionMap = {
            'revise': { url: '/admin/monitoring/revise', title: 'Request Revision', btnClass: 'btn-warning' },
            'decline': { url: '/admin/monitoring/decline', title: 'Decline Submission', btnClass: 'btn-danger' }
        };
        const data = actionMap[action];
        form.action = data.url;
        document.getElementById('remarksModalTitle').textContent = data.title;
        document.getElementById('remarksSubmitBtn').className = 'btn ' + data.btnClass;
        document.getElementById('remarksSubmitBtn').textContent = data.title;
        document.getElementById('remarksModal').style.display = 'flex';
    }

    function closeRemarksModal() {
        document.getElementById('remarksModal').style.display = 'none';
    }

    // Close modal on outside click
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('remarksModal');
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
</script>