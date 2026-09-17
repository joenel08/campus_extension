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
        <input type="file" name="attachment" class="submission-input file-upload">
        <p style="font-size:12px; color:#6c757d; margin-top:5px;">Upload supporting documents (PDF, DOC, DOCX, max 10MB)</p>
    </div>

</div>