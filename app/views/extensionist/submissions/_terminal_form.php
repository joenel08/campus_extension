<?php
$terminal = $form_data['terminal_info'] ?? [];
?>

<div class="submission-grid">

    <!-- SECTION: TERMINAL REPORT -->
    <div class="submission-group full" style="background:#f8fafc; font-weight:700; font-size:18px; padding:15px 22px;">
        <i class="fas fa-check-circle"></i> Terminal Report
    </div>

    <div class="submission-group full">
        <label class="submission-label">Completion Date <span style="color:red;">*</span></label>
        <input type="date" name="completion_date" class="submission-input" value="<?= htmlspecialchars($terminal['completion_date'] ?? '') ?>" required>
    </div>

    <div class="submission-group full">
        <label class="submission-label">Overall Status <span style="color:red;">*</span></label>
        <select name="overall_status" class="submission-select" required>
            <option value="">Select Status</option>
            <option value="completed" <?= ($terminal['overall_status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
            <option value="on-going" <?= ($terminal['overall_status'] ?? '') === 'on-going' ? 'selected' : '' ?>>On-Going</option>
            <option value="discontinued" <?= ($terminal['overall_status'] ?? '') === 'discontinued' ? 'selected' : '' ?>>Discontinued</option>
        </select>
    </div>

    <div class="submission-group full">
        <label class="submission-label">Final Summary <span style="color:red;">*</span></label>
        <textarea name="final_summary" class="submission-textarea" rows="6" required><?= htmlspecialchars($terminal['final_summary'] ?? '') ?></textarea>
    </div>

    <div class="submission-group full">
        <label class="submission-label">Lessons Learned</label>
        <textarea name="lessons_learned" class="submission-textarea" rows="4"><?= htmlspecialchars($terminal['lessons_learned'] ?? '') ?></textarea>
    </div>

    <div class="submission-group full">
        <label class="submission-label">Recommendations</label>
        <textarea name="recommendations" class="submission-textarea" rows="4"><?= htmlspecialchars($terminal['recommendations'] ?? '') ?></textarea>
    </div>

    <!-- FILE ATTACHMENT -->
<div class="submission-group full">
    <label class="submission-label"><i class="fas fa-paperclip"></i> Attach File</label>

    <div style="margin-bottom:12px; padding:12px; background:#e8f0fe; border-radius:6px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
        <div style="display:flex; align-items:center; gap:10px;">
            <i class="fas fa-file-word" style="font-size:20px; color:#2563eb;"></i>
            <div>
                <strong>Terminal Report Template</strong>
                <p style="font-size:12px; color:#6c757d; margin:2px 0 0 0;">Download and fill out the official terminal report format.</p>
            </div>
        </div>
        <a href="/templates/terminal_report_template.docx" download class="btn btn-sm btn-primary" style="text-decoration:none; padding:8px 16px; background:#2563eb; color:#fff; border-radius:6px; font-weight:600; display:inline-flex; align-items:center; gap:6px;">
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