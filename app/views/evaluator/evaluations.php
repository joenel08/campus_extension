<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="card">
    <div class="table-header">
        <h2><i class="fas fa-tasks"></i> Assigned Proposals for Evaluation</h2>
        <p class="table-subtitle">Evaluate the proposal, progress reports, and terminal report</p>
    </div>

    <?php if (empty($grouped)): ?>
        <p style="padding:20px; text-align:center; color:#777;">No proposals assigned to you.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Proposal Title</th>
                    <th>Extensionist</th>
                    <th>Proposal</th>
                    <th>Progress Reports</th>
                    <th>Terminal Report</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($grouped as $data): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($data['proposal_title']) ?></strong></td>
                        <td><?= htmlspecialchars($data['extensionist_name']) ?></td>

                        <!-- Proposal -->
                        <td>
                            <?php if ($data['proposal_evaluated']): ?>
                                <span class="badge <?= $data['proposal_vote'] === 'approve' ? 'badge-approved' : ($data['proposal_vote'] === 'revision' ? 'badge-pending' : 'badge-declined') ?>">
                                    <?= ucfirst($data['proposal_vote']) ?>
                                </span>
                                <a href="/evaluator/evaluate?id=<?= $data['submission_id'] ?>&type=proposal" class="btn btn-sm btn-edit">
                                    <i class="fas fa-eye"></i> 
                                </a>
                            <?php else: ?>
                                <span class="badge badge-pending">Pending</span>
                                <a href="/evaluator/evaluate?id=<?= $data['submission_id'] ?>&type=proposal" class="btn btn-sm btn-primary">
                                    <i class="fas fa-pen"></i> Evaluate
                                </a>
                            <?php endif; ?>
                        </td>

                        <!-- Progress Reports -->
                        <td>
                            <?php if (!empty($data['progress_reports'])): ?>
                                <?php foreach ($data['progress_reports'] as $idx => $pr): ?>
                                    <div style="display:flex; align-items:center; gap:6px; margin:3px 0; flex-wrap:wrap;">
                                        <span style="font-size:13px; min-width:80px;">R<?= $idx + 1 ?></span>
                                        <?php if ($pr['evaluated']): ?>
                                            <span class="badge <?= $pr['vote'] === 'approve' ? 'badge-approved' : ($pr['vote'] === 'revision' ? 'badge-pending' : 'badge-declined') ?>">
                                                <?= ucfirst($pr['vote']) ?>
                                            </span>
                                            <a href="/evaluator/evaluate?id=<?= $pr['id'] ?>&type=progress" class="btn btn-sm btn-edit">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="badge badge-pending">Pending</span>
                                            <a href="/evaluator/evaluate?id=<?= $pr['id'] ?>&type=progress" class="btn btn-sm btn-primary">
                                                <i class="fas fa-pen"></i> Evaluate
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span style="color:#999; font-size:13px;">No progress reports</span>
                            <?php endif; ?>
                        </td>

                        <!-- Terminal Report -->
                        <td>
                            <?php if ($data['terminal_report']): ?>
                                <?php $term = $data['terminal_report']; ?>
                                <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                                    <?php if ($term['evaluated']): ?>
                                        <span class="badge <?= $term['vote'] === 'approve' ? 'badge-approved' : ($term['vote'] === 'revision' ? 'badge-pending' : 'badge-declined') ?>">
                                            <?= ucfirst($term['vote']) ?>
                                        </span>
                                        <a href="/evaluator/evaluate?id=<?= $term['id'] ?>&type=terminal" class="btn btn-sm btn-edit">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="badge badge-pending">Pending</span>
                                        <a href="/evaluator/evaluate?id=<?= $term['id'] ?>&type=terminal" class="btn btn-sm btn-primary">
                                            <i class="fas fa-pen"></i> Evaluate
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <span style="color:#999; font-size:13px;">Not yet submitted</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>