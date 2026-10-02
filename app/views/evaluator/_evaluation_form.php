<?php
$readonly = $readonly ?? false;
?>

<div style="background:#fff; padding:20px; border-radius:8px; border:1px solid #e5e7eb;">
    <h3 style="color:#183153; border-bottom:2px solid #e5e7eb; padding-bottom:10px;">
        <i class="fas fa-check-circle"></i> Evaluation Form
    </h3>

    <form method="POST" action="/evaluator/save-evaluation">
        <input type="hidden" name="submission_id" value="<?= $submission_id ?>">
        <input type="hidden" name="report_type" value="<?= $report_type ?>">

        <h4 style="margin:15px 0 10px;">Final Decision</h4>

        <div style="display:flex; flex-direction:column; gap:10px; margin-bottom:20px;">
            <label style="cursor:<?= $readonly ? 'default' : 'pointer' ?>; padding:12px; border:1px solid #e5e7eb; border-radius:8px; display:flex; align-items:center; gap:10px; background:<?= $selectedVote === 'approve' ? '#e6f7ea' : '#fff' ?>;">
                <input type="radio" name="vote" value="approve" 
                    <?= $selectedVote === 'approve' ? 'checked' : '' ?> 
                    <?= $readonly ? 'disabled' : '' ?> required>
                <strong style="color:#16a34a;">Approved</strong>
            </label>

            <label style="cursor:<?= $readonly ? 'default' : 'pointer' ?>; padding:12px; border:1px solid #e5e7eb; border-radius:8px; display:flex; align-items:center; gap:10px; background:<?= $selectedVote === 'revision' ? '#fff7e6' : '#fff' ?>;">
                <input type="radio" name="vote" value="revision" 
                    <?= $selectedVote === 'revision' ? 'checked' : '' ?> 
                    <?= $readonly ? 'disabled' : '' ?> required>
                <strong style="color:#d97706;">Needs Improvement</strong>
            </label>

            <!-- <label style="cursor:<?= $readonly ? 'default' : 'pointer' ?>; padding:12px; border:1px solid #e5e7eb; border-radius:8px; display:flex; align-items:center; gap:10px; background:<?= $selectedVote === 'decline' ? '#f8d7da' : '#fff' ?>;">
                <input type="radio" name="vote" value="decline" 
                    <?= $selectedVote === 'decline' ? 'checked' : '' ?> 
                    <?= $readonly ? 'disabled' : '' ?> required>
                <strong style="color:#dc3545;">Declined</strong>
            </label> -->
        </div>

        <div style="margin-bottom:20px;">
            <label style="font-weight:700;">Comments</label>
            <textarea name="comments" rows="5" 
                style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; margin-top:5px; resize:vertical;" 
                placeholder="Enter your feedback here..." 
                <?= $readonly ? 'readonly' : '' ?>><?= htmlspecialchars($comments) ?></textarea>
        </div>

        <div style="display:flex; gap:10px;">
            <?php if (!$readonly): ?>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Submit Evaluation
                </button>
            <?php else: ?>
                <span style="color:#999; padding:10px 0;">This report is finalized. You can only view.</span>
            <?php endif; ?>
            <a href="/evaluator/evaluations" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>