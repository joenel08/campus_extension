<?php

$report_type = $report_type ?? 'proposal';
?>

<div class="card" style="padding:20px;">
    <input type="hidden" name="report_type" value="<?= $report_type ?>">
    <h2 style="color:#183153;">
        <i class="fas fa-clipboard-check"></i> 
        <?= $report_type === 'proposal' ? 'Evaluate Proposal' : 'Evaluate ' . ucfirst($report_type) . ' Report' ?>
    </h2>
    <p style="color:#6b7280; margin-bottom:20px;">
        <?= htmlspecialchars($proposal_title ?? 'N/A') ?> – submitted by <?= htmlspecialchars($extensionist_name ?? 'N/A') ?>
    </p>

   <div style="display:flex; gap:30px; flex-wrap:wrap;">
    <div style="flex:1.2; min-width:300px;">
        <?php include __DIR__ . '/_submission_details.php'; ?>
    </div>
    <div style="flex:1; min-width:300px;">
        <?php include __DIR__ . '/_evaluation_form.php'; ?>
    </div>
</div>
</div>

