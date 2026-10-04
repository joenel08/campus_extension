<?php
$form_data   = $form_data   ?? [];
$report_type = $report_type ?? 'proposal';
?>

<style>
    
</style>
<div class="submission-card">
    <div style="padding:22px; border-bottom:1px solid #dfe5ec;">
        <h2 style="color:#183153;"><i class="fas fa-edit"></i> Edit Submission</h2>
        <p style="color:#6b7280;">Update your submission details.</p>
    </div>

    <form method="POST" action="/extensionist/submissions/update"
          enctype="multipart/form-data"
          class="confirm-form"
          data-title="Resubmit for review?"
          data-message="Your updated submission will be sent back to the evaluator for review. Make sure all details are correct before submitting."
          data-ok="Resubmit"
          data-variant="info">

        <input type="hidden" name="id" value="<?= $submission['id'] ?>">
        <input type="hidden" name="report_type" value="<?= $report_type ?>">
        <input type="hidden" name="status" value="submitted">

        <?php if ($report_type === 'proposal'): ?>
            <?php include __DIR__ . '/_form.php'; ?>
        <?php elseif ($report_type === 'progress'): ?>
            <?php include __DIR__ . '/_progress_form.php'; ?>
        <?php elseif ($report_type === 'terminal'): ?>
            <?php include __DIR__ . '/_terminal_form.php'; ?>
        <?php endif; ?>

        <div class="submit-area" style="display:flex; gap:15px; justify-content:flex-end; padding:22px; border-top:1px solid #dfe5ec;">
            <button type="submit" class="submit-paper-btn"
                    style="background:#16a34a; padding:12px 24px;">
                <i class="fas fa-paper-plane"></i> Resubmit for Review
            </button>
        </div>
    </form>
</div>