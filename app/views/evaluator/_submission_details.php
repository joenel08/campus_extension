<?php
/**
 * Displays the report being evaluated. Branches on $report_type.
 *
 * Expected vars:
 *   $report_type     string  — 'proposal' | 'detailed_proposal' | 'progress' | 'terminal'
 *   $submission      array   — report row
 *   $form_data       array   — decoded form_data (proposal/detailed) or []
 *   $parentFormData  array   — decoded parent proposal's form_data (progress/terminal/detailed)
 *   $proposal_title  string
 *   $extensionist_name string
 */

$report_type = $report_type ?? 'proposal';
?>

<?php if ($report_type === 'detailed_proposal'): ?>

    <!-- ============ DETAILED PROPOSAL ============ -->
    <div class="submission-group full" style="background:#f8fafc; font-weight:700; padding:15px 22px;">
        <i class="fas fa-file-contract"></i> Detailed Proposal
    </div>

    <div class="submission-group full">
        <p style="color:#6b7280; margin:0 0 6px;">
            <strong>Proposal:</strong> <?= htmlspecialchars($proposal_title ?? 'N/A') ?>
        </p>
        <p style="color:#6b7280; margin:0;">
            <strong>Submitted by:</strong> <?= htmlspecialchars($extensionist_name ?? 'N/A') ?>
        </p>
    </div>

    <?php if (!empty($parentFormData['basic_info']['project_title'])): ?>
        <div class="submission-group full">
            <label class="submission-label">Original Proposal Title</label>
            <p style="margin:0;"><?= htmlspecialchars($parentFormData['basic_info']['project_title']) ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($parentFormData['basic_info']['project_leader'])): ?>
        <div class="submission-group full">
            <label class="submission-label">Project Leader</label>
            <p style="margin:0;"><?= htmlspecialchars($parentFormData['basic_info']['project_leader']) ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($form_data['attachment'])): ?>
        <div class="submission-group full">
            <label class="submission-label">Attached Detailed Proposal</label>
            <div style="padding:12px; background:#e6f7ea; border-radius:6px; display:flex; align-items:center; gap:10px;">
                <i class="fas fa-file-alt" style="font-size:20px; color:#16a34a;"></i>
                <div style="flex:1;">
                    <a href="/<?= htmlspecialchars($form_data['attachment']) ?>" target="_blank" style="color:#2563eb;">
                        <?= htmlspecialchars(basename($form_data['attachment'])) ?>
                    </a>
                </div>
                <a href="/<?= htmlspecialchars($form_data['attachment']) ?>" download
                   class="btn btn-sm btn-primary"
                   style="padding:6px 12px; background:#16a34a; color:#fff; border-radius:6px; text-decoration:none;">
                    <i class="fas fa-download"></i>
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="submission-group full">
            <p style="color:#999;">No file attached.</p>
        </div>
    <?php endif; ?>

<?php elseif ($report_type === 'proposal'): ?>

    <!-- ============ PROPOSAL ============ -->
    <?php $basic = $form_data['basic_info'] ?? []; ?>

    <div class="submission-group full" style="background:#f8fafc; font-weight:700; padding:15px 22px;">
        <i class="fas fa-info-circle"></i> A. Basic Information
    </div>

    <div class="submission-group full">
        <label class="submission-label">Program / Project Title</label>
        <p style="margin:0;"><?= htmlspecialchars($basic['project_title'] ?? 'N/A') ?></p>
    </div>

    <div class="submission-group">
        <label class="submission-label">Project Leader</label>
        <p style="margin:0;"><?= htmlspecialchars($basic['project_leader'] ?? 'N/A') ?></p>
    </div>

    <div class="submission-group">
        <label class="submission-label">Implementing Campus</label>
        <p style="margin:0;"><?= htmlspecialchars($basic['implementing_campus'] ?? 'N/A') ?></p>
    </div>

    <div class="submission-group">
        <label class="submission-label">Lead Unit / College</label>
        <p style="margin:0;"><?= htmlspecialchars($basic['lead_unit'] ?? 'N/A') ?></p>
    </div>

    <div class="submission-group">
        <label class="submission-label">Cooperating Unit / College</label>
        <p style="margin:0;"><?= htmlspecialchars($basic['cooperating_unit'] ?? 'N/A') ?></p>
    </div>

    <div class="submission-group full">
        <label class="submission-label">Project Site</label>
        <p style="margin:0;"><?= htmlspecialchars($basic['project_site'] ?? 'N/A') ?></p>
    </div>

    <div class="submission-group full">
        <label class="submission-label">Cooperating Agencies</label>
        <p style="margin:0;"><?= nl2br(htmlspecialchars($basic['cooperating_agencies'] ?? 'N/A')) ?></p>
    </div>

    <div class="submission-group">
        <label class="submission-label">Date Started</label>
        <p style="margin:0;"><?= htmlspecialchars($basic['date_started'] ?? 'N/A') ?></p>
    </div>

    <div class="submission-group">
        <label class="submission-label">Date Completed</label>
        <p style="margin:0;"><?= htmlspecialchars($basic['date_completed'] ?? 'N/A') ?></p>
    </div>

    <div class="submission-group full">
        <label class="submission-label">Status</label>
        <p style="margin:0;"><?= htmlspecialchars($basic['status'] ?? 'N/A') ?></p>
    </div>

    <div class="submission-group full">
        <label class="submission-label">Beneficiaries</label>
        <p style="margin:0;"><?= htmlspecialchars($basic['beneficiaries'] ?? 'N/A') ?></p>
    </div>

    <div class="submission-group">
        <label class="submission-label">Funding Agency / ies</label>
        <p style="margin:0;"><?= htmlspecialchars($basic['funding_agency'] ?? 'N/A') ?></p>
    </div>

    <div class="submission-group">
        <label class="submission-label">Budget</label>
        <p style="margin:0;">
            <?= isset($basic['budget']) ? '₱ ' . number_format((float)$basic['budget'], 2) : 'N/A' ?>
        </p>
    </div>

    <!-- Components -->
    <?php $components = $form_data['components'] ?? []; ?>
    <?php if (!empty($components)): ?>
        <div class="submission-group full" style="background:#f8fafc; font-weight:700; padding:12px 22px;">
            <i class="fas fa-layer-group"></i> Project Components
        </div>
        <div class="submission-group full" style="padding:0; border:none;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:8px; border:1px solid #dfe5ec; text-align:left;">Component Title</th>
                        <th style="padding:8px; border:1px solid #dfe5ec; text-align:left;">Component Leader</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($components as $c): ?>
                        <tr>
                            <td style="padding:8px; border:1px solid #dfe5ec;"><?= htmlspecialchars($c['title'] ?? '') ?></td>
                            <td style="padding:8px; border:1px solid #dfe5ec;"><?= htmlspecialchars($c['leader'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php if (!empty($form_data['attachment'])): ?>
        <div class="submission-group full">
            <label class="submission-label">Attachment</label>
            <div style="padding:12px; background:#e6f7ea; border-radius:6px; display:flex; align-items:center; gap:10px;">
                <i class="fas fa-file-alt" style="font-size:20px; color:#16a34a;"></i>
                <div style="flex:1;">
                    <a href="/<?= htmlspecialchars($form_data['attachment']) ?>" target="_blank" style="color:#2563eb;">
                        <?= htmlspecialchars(basename($form_data['attachment'])) ?>
                    </a>
                </div>
                <a href="/<?= htmlspecialchars($form_data['attachment']) ?>" download
                   class="btn btn-sm btn-primary"
                   style="padding:6px 12px; background:#16a34a; color:#fff; border-radius:6px; text-decoration:none;">
                    <i class="fas fa-download"></i>
                </a>
            </div>
        </div>
    <?php endif; ?>

<?php elseif ($report_type === 'progress'): ?>

    <!-- ============ PROGRESS REPORT ============ -->
    <div class="submission-group full" style="background:#f8fafc; font-weight:700; padding:15px 22px;">
        <i class="fas fa-chart-line"></i> Progress Report
    </div>

    <div class="submission-group full">
        <p style="color:#6b7280; margin:0 0 6px;">
            <strong>Proposal:</strong> <?= htmlspecialchars($proposal_title ?? 'N/A') ?>
        </p>
        <p style="color:#6b7280; margin:0;">
            <strong>Submitted by:</strong> <?= htmlspecialchars($extensionist_name ?? 'N/A') ?>
        </p>
    </div>

    <div class="submission-group">
        <label class="submission-label">Report Date</label>
        <p style="margin:0;"><?= htmlspecialchars($submission['report_date'] ?? 'N/A') ?></p>
    </div>

    <div class="submission-group">
        <label class="submission-label">Submitted At</label>
        <p style="margin:0;"><?= safe_date($submission['created_at'] ?? null, 'M d, Y H:i') ?></p>
    </div>

    <?php if (!empty($submission['accomplishments'])): ?>
        <div class="submission-group full">
            <label class="submission-label">Accomplishments</label>
            <p style="margin:0;"><?= nl2br(htmlspecialchars($submission['accomplishments'])) ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($submission['issues'])): ?>
        <div class="submission-group full">
            <label class="submission-label">Issues Encountered</label>
            <p style="margin:0;"><?= nl2br(htmlspecialchars($submission['issues'])) ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($submission['next_plan'])): ?>
        <div class="submission-group full">
            <label class="submission-label">Next Plan</label>
            <p style="margin:0;"><?= nl2br(htmlspecialchars($submission['next_plan'])) ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($submission['attachment'])): ?>
        <div class="submission-group full">
            <label class="submission-label">Attachment</label>
            <div style="padding:12px; background:#e6f7ea; border-radius:6px; display:flex; align-items:center; gap:10px;">
                <i class="fas fa-file-alt" style="font-size:20px; color:#16a34a;"></i>
                <div style="flex:1;">
                    <a href="/<?= htmlspecialchars($submission['attachment']) ?>" target="_blank" style="color:#2563eb;">
                        <?= htmlspecialchars(basename($submission['attachment'])) ?>
                    </a>
                </div>
                <a href="/<?= htmlspecialchars($submission['attachment']) ?>" download
                   class="btn btn-sm btn-primary"
                   style="padding:6px 12px; background:#16a34a; color:#fff; border-radius:6px; text-decoration:none;">
                    <i class="fas fa-download"></i>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($parentFormData['basic_info']['project_title'])): ?>
        <div class="submission-group full" style="background:#f8fafc; padding:12px 22px; margin-top:10px;">
            <label class="submission-label">Original Proposal</label>
            <p style="margin:0; font-weight:600;"><?= htmlspecialchars($parentFormData['basic_info']['project_title']) ?></p>
        </div>
    <?php endif; ?>

<?php else: ?>

    <!-- ============ TERMINAL REPORT ============ -->
    <div class="submission-group full" style="background:#f8fafc; font-weight:700; padding:15px 22px;">
        <i class="fas fa-check-circle"></i> Terminal Report
    </div>

    <div class="submission-group full">
        <p style="color:#6b7280; margin:0 0 6px;">
            <strong>Proposal:</strong> <?= htmlspecialchars($proposal_title ?? 'N/A') ?>
        </p>
        <p style="color:#6b7280; margin:0;">
            <strong>Submitted by:</strong> <?= htmlspecialchars($extensionist_name ?? 'N/A') ?>
        </p>
    </div>

    <div class="submission-group">
        <label class="submission-label">Completion Date</label>
        <p style="margin:0;"><?= htmlspecialchars($submission['completion_date'] ?? 'N/A') ?></p>
    </div>

    <div class="submission-group">
        <label class="submission-label">Overall Status</label>
        <p style="margin:0;"><?= htmlspecialchars($submission['overall_status'] ?? 'N/A') ?></p>
    </div>

    <?php if (!empty($submission['final_summary'])): ?>
        <div class="submission-group full">
            <label class="submission-label">Final Summary</label>
            <p style="margin:0;"><?= nl2br(htmlspecialchars($submission['final_summary'])) ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($submission['lessons_learned'])): ?>
        <div class="submission-group full">
            <label class="submission-label">Lessons Learned</label>
            <p style="margin:0;"><?= nl2br(htmlspecialchars($submission['lessons_learned'])) ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($submission['recommendations'])): ?>
        <div class="submission-group full">
            <label class="submission-label">Recommendations</label>
            <p style="margin:0;"><?= nl2br(htmlspecialchars($submission['recommendations'])) ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($submission['attachment'])): ?>
        <div class="submission-group full">
            <label class="submission-label">Attachment</label>
            <div style="padding:12px; background:#e6f7ea; border-radius:6px; display:flex; align-items:center; gap:10px;">
                <i class="fas fa-file-alt" style="font-size:20px; color:#16a34a;"></i>
                <div style="flex:1;">
                    <a href="/<?= htmlspecialchars($submission['attachment']) ?>" target="_blank" style="color:#2563eb;">
                        <?= htmlspecialchars(basename($submission['attachment'])) ?>
                    </a>
                </div>
                <a href="/<?= htmlspecialchars($submission['attachment']) ?>" download
                   class="btn btn-sm btn-primary"
                   style="padding:6px 12px; background:#16a34a; color:#fff; border-radius:6px; text-decoration:none;">
                    <i class="fas fa-download"></i>
                </a>
            </div>
        </div>
    <?php endif; ?>

<?php endif; ?>