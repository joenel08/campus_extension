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

  <!-- FILE ATTACHMENT -->
<div class="submission-group full">
    <label class="submission-label"><i class="fas fa-paperclip"></i> Attach File</label>

    <div style="margin-bottom:12px; padding:12px; background:#e8f0fe; border-radius:6px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
        <div style="display:flex; align-items:center; gap:10px;">
            <i class="fas fa-file-word" style="font-size:20px; color:#2563eb;"></i>
            <div>
                <strong>Progress Report Template</strong>
                <p style="font-size:12px; color:#6c757d; margin:2px 0 0 0;">Download and fill out the official progress report format.</p>
            </div>
        </div>
        <a href="/templates/progress_report_template.docx" download class="btn btn-sm btn-primary" style="text-decoration:none; padding:8px 16px; background:#2563eb; color:#fff; border-radius:6px; font-weight:600; display:inline-flex; align-items:center; gap:6px;">
            <i class="fas fa-download"></i> Download Template
        </a>
    </div>

    <?php if (!empty($form_data['attachment'])): ?>
        <div style="margin-bottom:10px; padding:12px; background:#e6f7ea; border-radius:6px; display:flex; align-items:center; gap:10px;">
            <i class="fas fa-file-alt" style="font-size:20px; color:#16a34a;"></i>
            <div style="flex:1;">
                <strong>Current File:</strong>
                <a href="/<?= htmlspecialchars($form_data['attachment']) ?>" target="_blank" style="color:#2563eb; margin-left:5px;">
                    <?= basename($form_data['attachment']) ?>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <input type="file" name="attachment" class="submission-input file-upload">
    <p style="font-size:12px; color:#6c757d; margin-top:5px;">Upload supporting documents (PDF, DOC, DOCX, max 10MB)</p>
</div>
</div>