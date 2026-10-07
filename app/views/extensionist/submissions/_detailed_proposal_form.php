<?php
$attachment = $form_data['attachment'] ?? null;
?>
<div class="submission-grid">
    <div class="submission-group full" style="background:#f8fafc; font-weight:700; font-size:18px; padding:15px 22px;">
        <i class="fas fa-file-contract"></i> DETAILED PROPOSAL
    </div>

    <div class="submission-group full">
        <p style="color:#6b7280; margin-bottom:12px;">
            Upload the complete detailed proposal document (PDF, DOC, or DOCX — max 10MB).
            The admin will review and either approve, request revision, or decline it.
        </p>

        <?php if (!empty($attachment)): ?>
            <div style="margin-bottom:12px; padding:12px; background:#e6f7ea; border-radius:6px; display:flex; align-items:center; gap:10px;">
                <i class="fas fa-file-alt" style="font-size:20px; color:#16a34a;"></i>
                <div style="flex:1;">
                    <strong>Current File:</strong>
                    <a href="/<?= htmlspecialchars($attachment) ?>" target="_blank" style="color:#2563eb; margin-left:5px;">
                        <?= htmlspecialchars(basename($attachment)) ?>
                    </a>
                </div>
                <a href="/<?= htmlspecialchars($attachment) ?>" download class="btn btn-sm btn-primary" style="padding:6px 12px; background:#16a34a; color:#fff; border-radius:6px; text-decoration:none;">
                    <i class="fas fa-download"></i>
                </a>
            </div>
            <p style="font-size:12px; color:#6c757d; margin-bottom:5px;">Upload a new file to replace the current one:</p>
        <?php endif; ?>

        <label class="submission-label">
            <i class="fas fa-paperclip"></i> Attach Detailed Proposal
            <?php if (empty($attachment)): ?><span style="color:red;">*</span><?php endif; ?>
        </label>
        <input type="file" name="attachment" class="submission-input file-upload" <?= empty($attachment) ? 'required' : '' ?> accept=".pdf,.doc,.docx">
    </div>
</div>