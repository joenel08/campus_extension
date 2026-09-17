<?php
$progress = $form_data['progress_info'] ?? [];
?>

<div class="submission-grid">
    <div class="submission-group full" style="background:#f8fafc; font-weight:700; font-size:18px; padding:15px 22px;">
        <i class="fas fa-chart-line"></i> Progress Report
    </div>

    <div class="submission-group full">
        <label class="submission-label">Reporting Period (Date)</label>
        <input type="date" name="report_date" class="submission-input" value="<?= htmlspecialchars($progress['report_date'] ?? '') ?>" required>
    </div>

    <div class="submission-group full">
        <label class="submission-label">Accomplishments</label>
        <textarea name="accomplishments" class="submission-textarea" rows="5" required><?= htmlspecialchars($progress['accomplishments'] ?? '') ?></textarea>
    </div>

    <div class="submission-group full">
        <label class="submission-label">Issues / Challenges</label>
        <textarea name="issues" class="submission-textarea" rows="3"><?= htmlspecialchars($progress['issues'] ?? '') ?></textarea>
    </div>

    <div class="submission-group full">
        <label class="submission-label">Next Plan</label>
        <textarea name="next_plan" class="submission-textarea" rows="3"><?= htmlspecialchars($progress['next_plan'] ?? '') ?></textarea>
    </div>

    <div class="submission-group full">
        <label class="submission-label"><i class="fas fa-paperclip"></i> Attach File</label>
        <input type="file" name="attachment" class="submission-input file-upload">
        <p style="font-size:12px; color:#6c757d; margin-top:5px;">Upload supporting documents (PDF, DOC, DOCX, max 10MB)</p>
    </div>
</div>