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
        <div>
            <h2>
                <i class="fas fa-filter"></i>
                <?php if ($filter === 'pending'): ?>
                    My Pending Submissions
                <?php elseif ($filter === 'completed'): ?>
                    My Completed Submissions
                <?php else: ?>
                    All My Submissions
                <?php endif; ?>
            </h2>
            <p class="table-subtitle">
                <?= count($submissions) ?> submission(s) found
            </p>
        </div>
        <a href="/extensionist/dashboard" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <?php if (empty($submissions)): ?>
        <p style="padding:20px; text-align:center; color:#777;">No submissions found in this category.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Academic Year</th>
                    <th>Proposal Title</th>
                    <th>Status</th>
                    <th>Progress Reports</th>
                    <th>Terminal Report</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($submissions as $index => $s): ?>
                    <?php
                    $statusClass = [
                        'draft' => 'badge-pending',
                        'submitted' => 'badge-approved',
                        'approved' => 'badge-approved',
                        'rejected' => 'badge-declined',
                        'revision' => 'badge-pending',
                        'pending_evaluation' => 'badge-pending',
                        'under_evaluation' => 'badge-approved',
                    ];
                    ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                         <td><?= htmlspecialchars($s['academic_year_label'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($s['proposal_title'] ?? 'N/A') ?></td>
                        <td>
                            <span class="badge <?= $statusClass[$s['status']] ?? 'badge-pending' ?>">
                                <?= ucfirst(str_replace('_', ' ', $s['status'])) ?>
                            </span>
                        </td>
                        <td>
                            <?php if (!empty($s['progress_reports'])): ?>
                                <?php foreach ($s['progress_reports'] as $idx => $pr): ?>
                                    <div style="display:flex; align-items:center; gap:5px; margin:2px 0;">
                                        <span style="font-size:12px;">R<?= $idx + 1 ?></span>
                                        <span class="badge <?= $statusClass[$pr['status']] ?? 'badge-pending' ?>">
                                            <?= ucfirst($pr['status']) ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span style="color:#999; font-size:12px;">None</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($s['terminal_report']): ?>
                                <span class="badge <?= $statusClass[$s['terminal_report']['status']] ?? 'badge-pending' ?>">
                                    <?= ucfirst($s['terminal_report']['status']) ?>
                                </span>
                            <?php else: ?>
                                <span style="color:#999; font-size:12px;">Not submitted</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('M d, Y', strtotime($s['created_at'])) ?></td>
                        <td>
                            <a href="/extensionist/submissions/show?id=<?= $s['id'] ?>" class="btn btn-sm btn-edit">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>