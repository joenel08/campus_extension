<?php
if (empty($selected_submission_id)) {
    header('Location: /extensionist/submissions');
    exit;
}

$form_data = $form_data ?? [];
$report_type = $report_type ?? 'proposal';
?>
<div class="submission-card">
    <div style="padding:22px; border-bottom:1px solid #dfe5ec;">
        <h2 style="color:#183153;">
            <i class="fas <?= $report_type === 'proposal' ? 'fa-plus-circle' : ($report_type === 'progress' ? 'fa-chart-line' : 'fa-check-circle') ?>"></i>
            New <?= ucfirst($report_type) ?> Submission
        </h2>
        <p style="color:#6b7280;">
            <?php if ($report_type === 'proposal'): ?>
                Fill out the form below for the selected proposal call.
            <?php elseif ($report_type === 'progress'): ?>
                Submit a progress report for the approved proposal.
            <?php else: ?>
                Submit the final terminal report for the approved proposal.
            <?php endif; ?>
        </p>
    </div>

    <form method="POST" action="/extensionist/submissions/store" enctype="multipart/form-data" id="submissionForm">
        <input type="hidden" name="submission_id" value="<?= $selected_submission_id ?>">
        <input type="hidden" name="report_type" value="<?= $report_type ?>">
        <input type="hidden" name="status" value="draft">

        <?php if ($report_type === 'proposal'): ?>
            <?php include __DIR__ . '/_proposal_form.php'; ?>
        <?php elseif ($report_type === 'progress'): ?>
            <?php include __DIR__ . '/_progress_form.php'; ?>
        <?php elseif ($report_type === 'terminal'): ?>
            <?php include __DIR__ . '/_terminal_form.php'; ?>
        <?php endif; ?>

        <div class="submit-area" style="display:flex; gap:15px; justify-content:flex-end; padding:22px; border-top:1px solid #dfe5ec;">
            <button type="submit" class="submit-paper-btn" style="background:#6c757d; padding:12px 24px;"><i class="fas fa-save"></i> Save Draft</button>
            <button type="submit" name="status" value="submitted" class="submit-paper-btn" style="background:#16a34a; padding:12px 24px;" onclick="return confirm('Submit this for review?')">
                <i class="fas fa-paper-plane"></i> Submit for Review
            </button>
        </div>
    </form>
</div>