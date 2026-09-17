<?php
$form_data = json_decode($submission['form_data'], true);
?>

<div class="submission-card">
    <div style="padding:22px; border-bottom:1px solid #dfe5ec;">
        <h2 style="color:#183153;"><i class="fas fa-edit"></i> Edit Submission</h2>
        <p style="color:#6b7280;">Update your submission details.</p>
    </div>

    <form method="POST" action="/extensionist/submissions/update" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $submission['id'] ?>">

        <?php include __DIR__ . '/_form.php'; ?>

        <div class="submit-area" style="display:flex; gap:15px; justify-content:flex-end; padding:22px; border-top:1px solid #dfe5ec;">
            <button type="submit" class="submit-paper-btn" style="background:#6c757d; padding:12px 24px;"><i class="fas fa-save"></i> Update Draft</button>
            <button type="submit" name="status" value="submitted" class="submit-paper-btn" style="background:#16a34a; padding:12px 24px;" onclick="return confirm('Submit this for review?')">
                <i class="fas fa-paper-plane"></i> Submit for Review
            </button>
        </div>
    </form>
</div>