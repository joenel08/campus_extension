<div style="background:#fff; padding:20px; border-radius:8px; border:1px solid #e5e7eb;">
    <h3 style="color:#183153; border-bottom:2px solid #e5e7eb; padding-bottom:10px;">
        <i class="fas fa-check-circle"></i> Evaluation Form
    </h3>

    <form method="POST" action="/evaluator/save-evaluation">
        <input type="hidden" name="submission_id" value="<?= $submission_id ?>">
        <input type="hidden" name="report_type" value="<?= $report_type ?>"> 

        <?php if ($report_type === 'proposal'): ?>
            <h4 style="margin:15px 0 8px;">Criteria Ratings (1–5)</h4>
            <?php foreach ($groupedCriteria as $group): ?>
                <h5 style="margin:10px 0 5px; color:#4b5563;"><?= htmlspecialchars($group['group']['name']) ?></h5>
                <?php foreach ($group['criteria'] as $crit): ?>
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:6px; flex-wrap:wrap;">
                        <span style="flex:1; min-width:150px; font-size:14px;"><?= htmlspecialchars($crit['criteria_text']) ?></span>
                        <div style="display:flex; gap:4px;">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <label style="cursor:<?= $readonly ? 'default' : 'pointer' ?>; font-size:13px;">
                                    <input type="radio" name="ratings[<?= $crit['id'] ?>]" value="<?= $i ?>" 
                                        <?= (isset($ratingMap[$crit['id']]) && $ratingMap[$crit['id']] == $i) ? 'checked' : '' ?> 
                                        <?= $readonly ? 'disabled' : '' ?> required>
                                    <?= $i ?>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <h4 style="margin:20px 0 10px;">Final Decision</h4>
        <div style="display:flex; gap:20px; margin-bottom:15px; flex-wrap:wrap;">
            <label style="cursor:<?= $readonly ? 'default' : 'pointer' ?>;">
                <input type="radio" name="vote" value="approve" <?= $selectedVote === 'approve' ? 'checked' : '' ?> <?= $readonly ? 'disabled' : '' ?> required> Approve
            </label>
            <label style="cursor:<?= $readonly ? 'default' : 'pointer' ?>;">
                <input type="radio" name="vote" value="revision" <?= $selectedVote === 'revision' ? 'checked' : '' ?> <?= $readonly ? 'disabled' : '' ?> required> Needs Revision
            </label>
            <label style="cursor:<?= $readonly ? 'default' : 'pointer' ?>;">
                <input type="radio" name="vote" value="decline" <?= $selectedVote === 'decline' ? 'checked' : '' ?> <?= $readonly ? 'disabled' : '' ?> required> Decline
            </label>
        </div>

        <div style="margin-bottom:20px;">
            <label><strong>Comments</strong></label>
            <textarea name="comments" rows="4" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;" <?= $readonly ? 'readonly' : '' ?>><?= htmlspecialchars($comments) ?></textarea>
        </div>

        <?php if (!$readonly): ?>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Submit Evaluation</button>
        <?php else: ?>
            <span style="color:#999;">This report is finalized. You can only view.</span>
        <?php endif; ?>
        <a href="/evaluator/dashboard" class="btn btn-secondary" style="margin-left:10px;">Cancel</a>
    </form>
</div>